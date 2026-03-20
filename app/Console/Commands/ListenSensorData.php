<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SensorDataService;
use React\EventLoop\Loop;
use React\Socket\Connector;
use Ratchet\RFC6455\Messaging\Frame;
use Ratchet\RFC6455\Messaging\MessageBuffer;
use Ratchet\RFC6455\Messaging\CloseFrameChecker;
use Ratchet\RFC6455\Handshake\ClientNegotiator;
use GuzzleHttp\Psr7\Request;

class ListenSensorData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:listen-sensor-data {--device= : Specific device ID to listen for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen for sensor data via WebSocket (Reverb) and store it in the database';

    protected $service;

    public function __construct(SensorDataService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     */
    public function handle()
{
    $host = '127.0.0.1'; // force IPv4, localhost resolves to IPv6 first on this system
    $port = config('reverb.servers.reverb.port', 8080);
    $key = config('reverb.apps.apps.0.key');

    $url = "ws://{$host}:{$port}/app/{$key}?protocol=7&client=js&version=8.4.0-rc2&flash=false";

    $this->info("Connecting to WebSocket at: {$url}");

    $loop = Loop::get();
    $connector = new Connector($loop);
    $negotiator = new ClientNegotiator(new \GuzzleHttp\Psr7\HttpFactory());

    $connector->connect("{$host}:{$port}")->then(function ($stream) use ($url, $negotiator) {
        $this->stream = $stream;
        $uri = new \GuzzleHttp\Psr7\Uri($url);
        $request = $negotiator->generateRequest($uri);

        // ✅ Serialize PSR-7 Request into raw HTTP string
        $rawRequest = $request->getMethod() . ' '
            . $request->getRequestTarget() . ' HTTP/'
            . $request->getProtocolVersion() . "\r\n";

        foreach ($request->getHeaders() as $name => $values) {
            $rawRequest .= $name . ': ' . implode(', ', $values) . "\r\n";
        }
        $rawRequest .= "\r\n";

        // TEMPORARY DEBUG LINE
        $this->line("<fg=yellow>Sending handshake:\n{$rawRequest}</>");

        $stream->write($rawRequest);

        $buffer = new MessageBuffer(
            new CloseFrameChecker(),
            function ($message) {
                $this->handleMessage($message->getPayload());
            },
            null,
            false // expectMask = false for client-side
        );

        $handshakeBuffer = '';
        $isUpgraded = false;

        $stream->on('data', function ($data) use ($buffer, &$handshakeBuffer, &$isUpgraded, $negotiator, $request) {
            if ($isUpgraded) {
                $buffer->onData($data);
                return;
            }

            $handshakeBuffer .= $data;
            if (strpos($handshakeBuffer, "\r\n\r\n") !== false) {
                $parts = explode("\r\n\r\n", $handshakeBuffer, 2);
                $responseStr = $parts[0] . "\r\n\r\n";
                $remainingData = $parts[1] ?? '';

                try {
                    $response = \GuzzleHttp\Psr7\Message::parseResponse($responseStr);
                    $negotiator->validateResponse($request, $response);
                    
                    $this->info("Handshake successful! Upgraded to WebSocket.");
                    $isUpgraded = true;
                    
                    if ($remainingData !== '') {
                        $buffer->onData($remainingData);
                    }
                } catch (\Exception $e) {
                    $this->error("Handshake failed: " . $e->getMessage());
                    $this->stream->close();
                }
            }
        });

        $stream->on('error', function (\Exception $e) {
            $this->error("Stream error: " . $e->getMessage());
        });

        $stream->on('close', function () {
            $this->warn("Connection closed.");
        });

        $this->info("Handshake sent. Waiting for connection...");

    }, function ($e) {
        $this->error("Could not connect: " . $e->getMessage());
    });

    $loop->run();
}

    protected $stream;

    protected function handleMessage($payload)
    {
        $msg = json_decode($payload, true);
    
        if (!$msg || !isset($msg['event'])) return;

        // 1. Connection Established
        if ($msg['event'] === 'pusher:connection_established') {
            $this->info("Connection established. Subscribing to channels...");
            $this->subscribeToChannels();
            return;
        }

        // 2. Handle Ping (Must respond with Pong to stay alive)
        if ($msg['event'] === 'pusher:ping') {
            $this->stream->write($this->mask(json_encode(['event' => 'pusher:pong'])));
            return;
        }

        // 3. Handle Sensor Readings (Whispers/Client Events)
        if ($msg['event'] === 'client-sensor-reading' || str_ends_with($msg['event'], 'SensorDataUpdated')) {
            try {
                $data = is_string($msg['data']) ? json_decode($msg['data'], true) : $msg['data'];
                
                if (isset($data['device_id'], $data['temperature'], $data['humidity'], $data['soil_moisture'])) {
                    $this->info("Received sensor data via WS: " . json_encode($data));
                    $this->service->process($data);
                    $this->info("Data processed and saved.");
                }
            } catch (\Exception $e) {
                $this->error("Error processing data: " . $e->getMessage());
            }
        }
    }

    protected function subscribeToChannels()
    {
        $greenhouses = \App\Models\Greenhouse::all();
        
        foreach ($greenhouses as $gh) {
            $channel = "control.{$gh->product_id}";
            $this->line("Subscribing to: {$channel}");
            
            $subscribeMsg = json_encode([
                'event' => 'pusher:subscribe',
                'data' => [
                    'channel' => $channel
                ]
            ]);

            $this->stream->write($this->mask($subscribeMsg));
        }
    }

    protected function mask($payload)
    {
        $frame = new Frame($payload);
        $frame->maskPayload();
        return $frame->getContents();
    }


}
