<?php

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_user', function (Blueprint $table) {
            $table->id();
            $table->integer('points');
            $table->time('complete_time');
            $table->foreignIdFor(Quiz::class)->constrained("quizzes")->onDelete("cascade");
            $table->foreignIdFor(User::class)->constrained("users")->onDelete("cascade");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_user');
    }
};
