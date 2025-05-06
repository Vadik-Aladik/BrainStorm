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
        Schema::create('user_answers', function (Blueprint $table) {
            // $table->id();
            // $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            // $table->foreignId('quest_id')->constrained('questions')->onDelete('cascade');
            // $table->foreignId('answer_id')->constrained('answers')->onDelete('cascade');
            // // $table->foreignId('test_id');
            // // $table->foreignId('quest_id');
            // // $table->foreignId('answer_id');
            // $table->string('answer')->nullable();
            // $table->boolean('status');
            // $table->timestamps();
            $table->id();
            $table->foreignId('test_id')->constrained('user_tests')->onDelete('cascade');
            $table->foreignId('answer_id')->constrained('answers')->onDelete('cascade');
            $table->string('answer')->nullable();
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
