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
    Schema::create('whm_servers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('api_url');
        $table->string('api_token');
        $table->string('username');
        $table->boolean('active')->default(true);
        $table->integer('priority')->default(1);
        $table->timestamps();
    });
}

};
