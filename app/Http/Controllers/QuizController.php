<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\QueryFilters\MyQuizzesFilter;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QuizController extends Controller
{
    public function getQuizzes(): AnonymousResourceCollection
    {
        $perPage = request('per_page', 9);

        $quizzes = QueryBuilder::for(Quiz::class)
            ->with(['categories', 'difficulty'])
            ->withCount(['points', 'questions'])
            ->allowedFilters([
                AllowedFilter::exact('categories.id'),
                'title',
                'difficulty_id',
                AllowedFilter::custom('my_quizzes', new MyQuizzesFilter),
            ])
            ->allowedSorts(['title', 'created_at', 'total_users', 'id'])
            ->cursorPaginate($perPage)->appends(request()->query());

        return QuizResource::collection($quizzes);
    }

    public function getQuiz($id): QuizResource
    {
        $quiz = Quiz::with(['categories', 'difficulty', 'questions.answers'])
            ->withCount(['points', 'questions'])
            ->findOrFail($id);

        request()->merge([
            'filter' => [
                'my_quizzes'    => false,
                'categories.id' => $quiz->categories->pluck('id')->implode(','),
            ],
        ]);

        $similarQuizzes = QueryBuilder::for(Quiz::class)
            ->with(['categories', 'difficulty'])
            ->allowedFilters([
                AllowedFilter::exact('categories.id'),
                AllowedFilter::custom('my_quizzes', new MyQuizzesFilter),
            ])
            ->where('id', '!=', $quiz->id)
            ->limit(3)
            ->get();

        return (new QuizResource($quiz))->additional([
            'similar_quizzes' => QuizResource::collection($similarQuizzes),
        ]);
    }

    public function startQuiz($id): JsonResponse
    {
        $user = Auth::user();

        $pastAttempt = DB::table('quiz_user')
            ->where('quiz_id', $id)
            ->where('user_id', $user?->id)
            ->exists();

        if ($pastAttempt) {
            return response()->json(['message' => 'User has already completed this quiz'], 409);
        }

        $attemptId = DB::table('quiz_attempts')->insertGetId([
            'quiz_id'    => $id,
            'user_id'    => $user?->id,
            'start_time' => now(),
        ]);

        return response()->json(['attempt_id' => $attemptId]);
    }

    public function endQuiz($id): JsonResponse
    {
        $attemptId = request('attempt_id');
        $answers = request('answers');

        $quiz = Quiz::findOrFail($id);

        DB::table('quiz_attempts')
            ->where('id', $attemptId)
            ->update(['end_time' => now()]);

        $attempt = DB::table('quiz_attempts')->where('id', $attemptId)->first();

        $startTime = Carbon::parse($attempt->start_time);
        $endTime = Carbon::parse($attempt->end_time);
        $diffInSeconds = $startTime->diffInSeconds($endTime);

        $answerIds = collect($answers)
            ->filter()
            ->flatten()
            ->all();

        $points = DB::table('answers')
            ->whereIn('id', $answerIds)
            ->where('is_correct', true)
            ->count();

        $user = Auth::user();
        if ($user) {
            if ($user->hasVerifiedEmail()) {
                Quiz::where('id', $id)->increment('total_users');
            }

            DB::table('quiz_user')->insert([
                'points'        => $points,
                'complete_time' => gmdate('H:i:s', $diffInSeconds),
                'quiz_id'       => $quiz->id,
                'user_id'       => $user->id,
                'created_at'    => now(),
            ]);
        }

        return response()->json([
            'result_points' => $points,
            'result_time'   => gmdate('H:i:s', $diffInSeconds),
        ]);
    }
}
