<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patchnotes', function (Blueprint $table) {
            $table->id();
            $table->string('title');      // 제목
            $table->string('summary');    // 주요 패치 기능 요약
            $table->text('content')->nullable(); // 본문
            $table->unsignedInteger('views')->default(0); // 조회수
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patchnotes');
    }
};
