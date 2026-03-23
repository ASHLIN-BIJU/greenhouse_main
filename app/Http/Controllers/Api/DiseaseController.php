<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use App\Services\DiseaseDetectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DiseaseController extends Controller
{
    protected $diseaseService;

    public function __construct(DiseaseDetectionService $diseaseService)
    {
        $this->diseaseService = $diseaseService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function detect(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {

            


            // Save image locally
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('diseases', $filename, 'public');

            // Call external API (or mock)
            $detectionData = $this->diseaseService->detect($path);

            // Store in database
            $disease = Disease::create(array_merge($detectionData, [
                'image_path' => $path,
            ]));

            return response()->json([
                'success' => true,
                'data' => $disease
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diseases = Disease::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $diseases
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $disease = Disease::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $disease
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Disease record not found'
            ], 404);
        }
    }
}
