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
    Schema::table('error_logs', function (Blueprint $table) {
        $table->string('title', 512)->change(); // 또는 text()도 가능
    });
}

public function down()
{
    Schema::table('error_logs', function (Blueprint $table) {
        $table->string('title', 255)->change();
    });
}
};
