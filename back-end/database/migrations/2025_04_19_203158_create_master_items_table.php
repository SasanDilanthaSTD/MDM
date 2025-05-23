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
        Schema::create('master_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('attachment')->nullable();
            $table->string('status', 8)->default('Active');
            $table->foreignId('category_id')->constrained('master_categories')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('master_brands')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_items');
    }
};
