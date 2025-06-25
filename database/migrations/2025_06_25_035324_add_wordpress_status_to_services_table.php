<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('wordpress_installed')->default(false)->after('status');
            $table->string('wordpress_version')->nullable()->after('wordpress_installed');
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['wordpress_installed', 'wordpress_version']);
        });
    }
};
