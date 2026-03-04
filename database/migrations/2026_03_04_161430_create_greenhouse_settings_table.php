<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('greenhouse_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('greenhouse_id')->constrained()->onDelete('cascade');
            $table->decimal('temperature_limit', 5, 2)->default(30.00);
            $table->decimal('humidity_limit', 5, 2)->default(70.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('greenhouse_settings');
    }
};
