<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set all parent_id values to NULL
        DB::table('categories')->update(['parent_id' => null]);
        
        // Remove the foreign key constraint
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']); // Remove foreign key constraint
        });
        
        // Modify the parent_id column to remove the constraint
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('parent_id'); // Remove the parent_id column entirely
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('slug');
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }
};
