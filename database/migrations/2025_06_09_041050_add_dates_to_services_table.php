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
        Schema::table('services', function (Blueprint $table) {
            $table->date('started_at')->nullable()->after('whm_server_id');
            $table->date('expired_at')->nullable()->after('started_at');
        });
    }
    
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'expired_at']);
        });
    }
    
};
