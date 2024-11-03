<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCoursesTable extends Migration
{
    public function up(): void
    {
        Schema::create('business_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->integer('purchased_seats');
            $table->decimal('total_price', 10, 2);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['business_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_courses');
    }
} 