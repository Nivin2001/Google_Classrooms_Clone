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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id(); // id BIGINT unsigned AUTO_INCREMENT PRIMARY
            $table->string('name', 255); // VARCHAR (4096)
            $table->string('code', 10)->unique(); // رح يحجز كل الطول حتى لو عبينا اقل
            $table->string('section')->nullable();
            $table->string('subject')->nullable();
            $table->string('room')->nullable();
            $table->string('cover_image_path')->nullable(); //رح يخزن الباث عادي كنص 
            // $table->binary('cover_image_path');//رح يخزن الباث كنص مشفر وبيكون casesensitive
            $table->string('theme')->nullable();
            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users','id')
            ->nullOnDelete(); 
            // $table->foreignId('user_id')
            //->nullable()//عشان عندي nullondelete لازم اعرفه انه nullable
            // ->constrained('users','id')
            // ->nullOnDelete();
            $table->enum('status',['active','archived'])->default('active');
            $table->timestamps();//created at + updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
