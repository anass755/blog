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
        Schema::table('services', function (Blueprint $table) {
            // Add status column (0 = disabled, 1 = enabled)
            $table->tinyInteger('status')->default(1)->after('description');
            
            // Optional: Remove is_active if you want to replace it
            // $table->dropColumn('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('status');
            
            // If you dropped is_active, add it back
            // $table->boolean('is_active')->default(true);
        });
    }
};