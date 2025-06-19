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
        $table->string('type', 50)->change(); // ENUM → VARCHAR
    });
}

public function down()
{
    Schema::table('error_logs', function (Blueprint $table) {
        $table->enum('type', ['server', 'api', 'integration', 'etc'])->change(); // 원래대로
    });
}
};
