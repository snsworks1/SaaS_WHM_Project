<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('ftp_accounts')->default(1);
            $table->integer('email_accounts')->default(5);
            $table->integer('sql_databases')->default(1);
            $table->integer('mailing_lists')->default(0);
            $table->integer('max_email_per_hour')->default(50);
            $table->integer('email_quota')->default(500);
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'ftp_accounts',
                'email_accounts',
                'sql_databases',
                'mailing_lists',
                'max_email_per_hour',
                'email_quota',
            ]);
        });
    }
};
