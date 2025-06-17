<?php

use App\Models\Difficulty;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->integer('total_users');
            $table->foreignIdFor(Difficulty::class)->constrained('difficulties')->onDelete('cascade');
            $table->string('image');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
