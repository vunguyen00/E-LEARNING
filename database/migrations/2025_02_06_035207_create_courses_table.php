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
        Schema::create('courses', function (Blueprint $table) {
            $table->id(); // Tự động tạo cột 'id' làm khóa chính
            $table->string('title');
            $table->longText('description');
            $table->unsignedBigInteger('mentor_id'); // Khóa ngoại
            $table->integer('price');
            $table->timestamp('available_at')->nullable(); // Ngày mở khóa học, có thể null
            $table->timestamps(); // Tự động tạo 'created_at' và 'updated_at'
        
            // Thiết lập khóa ngoại
            $table->foreign('mentor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
