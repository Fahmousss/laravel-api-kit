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
        Schema::create('anthropometry_standards', function (Blueprint $table) {
            $table->id();
            $table->enum('gender', ['L', 'P']); // Laki-laki / Perempuan
            $table->integer('age_in_months')->index();
            
            // Standard Deviation Cutoffs (Height in cm)
            $table->float('minus_3_sd');
            $table->float('minus_2_sd');
            $table->float('minus_1_sd');
            $table->float('median'); // 0 SD
            $table->float('plus_1_sd');
            $table->float('plus_2_sd');
            $table->float('plus_3_sd');
            
            $table->timestamps();

            // Only one row per gender and age
            $table->unique(['gender', 'age_in_months']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anthropometry_standards');
    }
};
