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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('slug')->unique(); 
            $table->string('logo_url')->nullable(); 
            $table->string('banner_url')->nullable(); 
            $table->text('address')->nullable(); 
            $table->string('shop_name');
            $table->longText('description');
            $table->decimal('commission_rate', 5, 2)->default(10.00); 
            $table->enum('status', ['pending', 'approved', 'suspanded'])->default('pending');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
