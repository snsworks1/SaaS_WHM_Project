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
        // database/migrations/xxxx_xx_xx_add_domain_limits_to_plans_table.php
Schema::table('plans', function (Blueprint $table) {
    $table->integer('addon_domains')->default(0);  // maxaddon
    $table->integer('subdomains')->default(0);     // maxsub
});

    }

};
