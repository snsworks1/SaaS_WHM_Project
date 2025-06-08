<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('whm_server_id')->nullable()->after('plan_id');
        $table->string('whm_username')->nullable()->after('whm_server_id');
        $table->string('whm_domain')->nullable()->after('whm_username');
    });
}

};
