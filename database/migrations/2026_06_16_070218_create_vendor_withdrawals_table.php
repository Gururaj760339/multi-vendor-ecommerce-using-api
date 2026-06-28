<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade'); // কোন ভেন্ডর টাকা তুলতে চাচ্ছে
            
            $table->decimal('amount', 10, 2); // কত টাকা উইথড্র করতে চায়
            $table->string('payment_method'); // bKash, Nagad, Rocket, or Bank Transfer
            $table->text('payment_details'); // ফোন নাম্বার বা ব্যাংক অ্যাকাউন্ট ডিটেইলস (JSON আকারেও রাখতে পারেন)
            
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // উইথড্র রিকোয়েস্টের অবস্থা
            $table->text('admin_note')->nullable(); // রিজেক্ট করলে কেন করলো বা ট্রানজেকশন আইডি অ্যাডমিন লিখে দিতে পারবে
            $table->timestamp('approved_at')->nullable(); // কখন অ্যাডমিন টাকাটা পাঠালো
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_withdrawals');
    }
};