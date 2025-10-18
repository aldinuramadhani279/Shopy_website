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
        // Hapus kolom is_approved dan ganti dengan status
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
        
        Schema::table('reviews', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_comment')->nullable(); // Kolom tambahan untuk komentar admin
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['status', 'admin_comment']);
        });
        
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false);
        });
    }
};
