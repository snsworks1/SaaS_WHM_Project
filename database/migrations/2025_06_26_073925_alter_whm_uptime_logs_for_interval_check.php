<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1단계: 외래키 제거
        Schema::table('whm_uptime_logs', function (Blueprint $table) {
            $table->dropForeign('whm_uptime_logs_whm_server_id_foreign');
        });

        // 2단계: 유니크 키 제거 + date → collected_at 변경
        Schema::table('whm_uptime_logs', function (Blueprint $table) {
            $table->dropUnique('whm_uptime_logs_whm_server_id_date_unique');
            $table->dropColumn('date');
            $table->dateTime('collected_at')->after('whm_server_id');
        });
    }
};
