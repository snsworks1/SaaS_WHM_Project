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
    Schema::table('payments', function (Blueprint $table) {
        $table->unsignedBigInteger('service_id')->nullable()->after('user_id');

        $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->dropForeign(['service_id']);
        $table->dropColumn('service_id');
    });
}
};
