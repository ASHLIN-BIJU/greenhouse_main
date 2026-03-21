<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('diseases')) {
            Schema::create('diseases', function (Blueprint $table) {
                $table->id();
                $table->string('disease_name');
                $table->text('description');
                $table->text('symptoms');
                $table->text('causes');
                $table->text('preventive_measures');
                $table->text('treatment');
                $table->float('confidence_value');
                $table->string('image_path');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diseases');
    }
};
