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
    Schema::create('webhook_logs', function (Blueprint $table) {
        $table->id();
        $table->string('event_type');
        $table->string('payment_key')->nullable();
        $table->string('order_id')->nullable();
        $table->string('email')->nullable();         // 사용자 이메일
        $table->string('whm_username')->nullable();  // 사용자 서버 계정
        $table->json('payload');                     // 전체 데이터 일부만 저장
        $table->timestamp('received_at')->nullable();
        $table->timestamps();
    });
}

};
