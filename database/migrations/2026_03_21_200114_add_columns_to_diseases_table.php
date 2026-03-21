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
        Schema::table('diseases', function (Blueprint $table) {
            if (!Schema::hasColumn('diseases', 'disease_name')) {
                $table->string('disease_name')->after('id');
            }
            if (!Schema::hasColumn('diseases', 'causes')) {
                $table->text('causes')->after('symptoms');
            }
            if (!Schema::hasColumn('diseases', 'preventive_measures')) {
                $table->text('preventive_measures')->after('causes');
            }
            if (!Schema::hasColumn('diseases', 'confidence_value')) {
                $table->float('confidence_value')->after('treatment');
            }
            if (!Schema::hasColumn('diseases', 'image_path')) {
                $table->string('image_path')->after('confidence_value');
            }
        });
    }

    public function down(): void
    {
        Schema::table('diseases', function (Blueprint $table) {
            $table->dropColumn(['disease_name', 'causes', 'preventive_measures', 'confidence_value', 'image_path']);
        });
    }
};
