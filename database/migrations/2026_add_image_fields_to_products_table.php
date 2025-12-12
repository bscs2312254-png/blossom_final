<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add image field if not exists (already exists, but we'll ensure)
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->after('price');
            }
        });
        
        Schema::table('categories', function (Blueprint $table) {
            // Add image field if not exists
            if (!Schema::hasColumn('categories', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};