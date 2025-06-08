<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('whm_servers', function (Blueprint $table) {
            $table->bigInteger('total_disk_capacity')->default(0)->after('username'); // 전체 용량 (GB)
            $table->bigInteger('used_disk_capacity')->default(0)->after('total_disk_capacity'); // 사용 용량 (GB)
        });
    }

    public function down(): void
    {
        Schema::table('whm_servers', function (Blueprint $table) {
            $table->dropColumn('total_disk_capacity');
            $table->dropColumn('used_disk_capacity');
        });
    }
};
