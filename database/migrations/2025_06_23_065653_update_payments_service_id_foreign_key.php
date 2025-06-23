<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // 기존 외래키 제약 제거
            $table->dropForeign(['service_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            // nullable + onDelete('set null')로 재설정
            $table->unsignedBigInteger('service_id')->nullable()->change();

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->unsignedBigInteger('service_id')->nullable(false)->change();

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('cascade'); // 원래대로 되돌림
        });
    }
};
