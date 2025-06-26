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
       // database/migrations/xxxx_xx_xx_create_whm_uptime_logs_table.php
Schema::create('whm_uptime_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('whm_server_id')->constrained()->onDelete('cascade');
    $table->date('date'); // 하루 기준
    $table->enum('status', ['up', 'down']);
    $table->integer('response_time_ms')->nullable();
    $table->timestamps();

    $table->unique(['whm_server_id', 'date']);
});

    }

};
