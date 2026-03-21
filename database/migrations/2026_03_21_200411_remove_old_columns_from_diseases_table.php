<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diseases', function (Blueprint $table) {
            if (Schema::hasColumn('diseases', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('diseases', 'external_id')) {
                $table->dropColumn('external_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('diseases', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('external_id')->nullable();
        });
    }
};
