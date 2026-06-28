<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade'); // ভেন্ডরের আইডি (ইউজার টেবিলের সাথে কানেক্টেড হলে)
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // মেইন অর্ডারের আইডি
            $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade'); // নির্দিষ্ট কোন আইটেম থেকে আয় হলো
            
            $table->decimal('gross_amount', 10, 2);  // টোটাল প্রোডাক্টের দাম (দাম * পরিমাণ)
            $table->decimal('commission_amount', 10, 2); // অ্যাডমিন যে কমিশন কেটে নিয়েছে
            $table->decimal('net_amount', 10, 2); // কমিশন বাদ দিয়ে ভেন্ডর যা পাবে (gross - commission)
            
            $table->enum('status', ['pending', 'available', 'refunded'])->default('pending'); // আর্নিং স্ট্যাটাস
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('vendor_earnings');
    }
};
