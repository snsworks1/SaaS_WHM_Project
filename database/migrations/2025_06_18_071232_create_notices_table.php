<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('importance'); // 중요도 (예: 높음/보통/낮음)
            $table->string('category');   // 종류 (점검/이벤트/안내/긴급점검)
            $table->string('title');      // 제목
            $table->text('content')->nullable(); // 본문
            $table->unsignedInteger('views')->default(0); // 조회수
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
