<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->unsignedBigInteger('plan_id'); // Plan 모델과 연동
        $table->string('order_id')->unique();  // Toss에서 받은 orderId
        $table->string('payment_key')->nullable(); // Toss의 paymentKey
        $table->integer('amount');
        $table->enum('status', ['PAID', 'FAILED', 'CANCELED'])->default('PAID');
        $table->timestamp('approved_at')->nullable();

        $table->timestamps();
    });
}

};
