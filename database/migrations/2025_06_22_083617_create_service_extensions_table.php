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
    Schema::create('service_extensions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('service_id')->constrained()->onDelete('cascade');
        $table->foreignId('payment_id')->constrained()->onDelete('cascade');
        $table->integer('amount'); // 원 단위 금액
        $table->integer('period'); // 개월 수
        $table->dateTime('extended_at');
        $table->string('order_id')->nullable(); // 결제 주문번호
        $table->timestamps();
    });
}

};
