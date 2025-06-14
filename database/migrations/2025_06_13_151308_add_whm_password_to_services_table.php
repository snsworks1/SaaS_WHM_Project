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
    Schema::table('services', function (Blueprint $table) {
        $table->text('whm_password')->nullable(); // 암호화된 비밀번호 저장
    });
}

public function down()
{
    Schema::table('services', function (Blueprint $table) {
        $table->dropColumn('whm_password');
    });
}
};
