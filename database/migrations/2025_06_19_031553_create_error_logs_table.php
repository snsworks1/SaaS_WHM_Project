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
        Schema::create('error_logs', function (Blueprint $table) {
    $table->id();

    $table->enum('level', ['low', 'medium', 'high'])->default('medium'); // 중요도
    $table->enum('type', ['server', 'integration', 'api', 'other'])->default('other'); // 종류
    $table->string('title'); // 오류명
    $table->string('file_path')->nullable(); // 발생 파일 경로
    $table->timestamp('occurred_at')->useCurrent(); // 발생 시간

    $table->unsignedBigInteger('server_id')->nullable(); // 발생 서버 ID (WHM 서버 FK)
    $table->string('whm_username')->nullable(); // WHM 계정명

    $table->boolean('resolved')->default(false); // 해결 여부
    $table->timestamp('resolved_at')->nullable(); // 해결 시간

    $table->timestamps();
});

    }

};
