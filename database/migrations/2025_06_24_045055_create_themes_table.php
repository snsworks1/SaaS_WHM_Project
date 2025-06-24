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
Schema::create('themes', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->string('zip_path');
    $table->string('screenshot_path')->nullable();
    $table->enum('plan_type', ['basic', 'pro', 'all'])->default('all');
    $table->timestamps();
});


    }

 
};
