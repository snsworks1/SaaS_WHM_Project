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
        $table->string('method')->nullable()->after('payment_key');
        // 예: CARD, ACCOUNT_TRANSFER, VIRTUAL_ACCOUNT, 휴대폰, 간편결제 등
    });
}
};
