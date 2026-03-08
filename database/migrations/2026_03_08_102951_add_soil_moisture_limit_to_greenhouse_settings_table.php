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
        Schema::table('greenhouse_settings', function (Blueprint $table) {
            $table->decimal('soil_moisture_limit', 5, 2)->default(40.00)->after('humidity_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('greenhouse_settings', function (Blueprint $table) {
            $table->dropColumn('soil_moisture_limit');
        });
    }
};
