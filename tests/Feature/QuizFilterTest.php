<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Difficulty;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_filter_quizzes_by_categories()
    {
        $categoryA = Category::factory()->create();
        $categoryB = Category::factory()->create();

        $quizA = Quiz::factory()->create();
        $quizB = Quiz::factory()->create();
        $quizC = Quiz::factory()->create();

        $quizA->categories()->attach($categoryA);
        $quizB->categories()->attach($categoryB);
        $quizC->categories()->detach();

        $response = $this->getJson('/api/quizzes?filter[categories.id]=' . $categoryA->id . ',' . $categoryB->id);
        $response->assertStatus(200);

        $data = $response->json('data');
        $quizIds = collect($data)->pluck('id')->all();

        $this->assertContains($quizA->id, $quizIds);
        $this->assertContains($quizB->id, $quizIds);
        $this->assertNotContains($quizC->id, $quizIds);
    }

    public function test_can_filter_quizzes_by_difficulty()
    {
        $difficultyA = Difficulty::factory()->create();
        $difficultyB = Difficulty::factory()->create();

        $quizA = Quiz::factory()->create(['difficulty_id' => $difficultyA->id]);
        $quizB = Quiz::factory()->create(['difficulty_id' => $difficultyB->id]);

        $response = $this->getJson('/api/quizzes?filter[difficulty_id]=' . $difficultyA->id);
        $response->assertStatus(200);

        $data = $response->json('data');
        $quizIds = collect($data)->pluck('id')->all();

        $this->assertContains($quizA->id, $quizIds);
        $this->assertNotContains($quizB->id, $quizIds);
    }

    public function test_can_filter_quizzes_by_title()
    {
        $quizA = Quiz::factory()->create(['title' => 'Alpha']);
        $quizB = Quiz::factory()->create(['title' => 'Bravo']);
        $quizC = Quiz::factory()->create(['title' => 'Alpha']);

        $response = $this->getJson('/api/quizzes?filter[title]=Alpha');
        $response->assertStatus(200);
        $quizIds = collect($response->json('data'))->pluck('id')->all();

        $this->assertContains($quizA->id, $quizIds);
        $this->assertContains($quizC->id, $quizIds);
        $this->assertNotContains($quizB->id, $quizIds);
    }

    public function test_can_filter_completed_and_not_completed_quizzes_for_user()
    {
        $user = User::factory()->create();
        $quizCompleted = Quiz::factory()->create();
        $quizNotCompleted = Quiz::factory()->create();

        $user->quizzes()->attach($quizCompleted->id, [
            'points'        => 10,
            'complete_time' => 100,
            'created_at'    => now(),
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/api/quizzes?filter[my_quizzes]=true');
        $response->assertStatus(200);
        $quizIds = collect($response->json('data'))->pluck('id')->all();

        $this->assertContains($quizCompleted->id, $quizIds);
        $this->assertNotContains($quizNotCompleted->id, $quizIds);

        $response = $this->getJson('/api/quizzes?filter[my_quizzes]=false');
        $response->assertStatus(200);
        $quizIds = collect($response->json('data'))->pluck('id')->all();

        $this->assertContains($quizNotCompleted->id, $quizIds);
        $this->assertNotContains($quizCompleted->id, $quizIds);
    }

    public function test_can_sort_quizzes_by_created_at()
    {
        $quizA = Quiz::factory()->create(['created_at' => now()->subDays(2)]);
        $quizB = Quiz::factory()->create(['created_at' => now()->subDay()]);
        $quizC = Quiz::factory()->create(['created_at' => now()]);

        $response = $this->getJson('/api/quizzes?sort=created_at');
        $response->assertStatus(200);
        $quizIds = collect($response->json('data'))->pluck('id')->all();
        $this->assertEquals([$quizA->id, $quizB->id, $quizC->id], $quizIds);

        $response = $this->getJson('/api/quizzes?sort=-created_at');
        $response->assertStatus(200);
        $quizIds = collect($response->json('data'))->pluck('id')->all();
        $this->assertEquals([$quizC->id, $quizB->id, $quizA->id], $quizIds);
    }

    public function test_can_sort_quizzes_by_total_users()
    {
        $quizA = Quiz::factory()->create(['total_users' => 1]);
        $quizB = Quiz::factory()->create(['total_users' => 5]);
        $quizC = Quiz::factory()->create(['total_users' => 3]);

        $response = $this->getJson('/api/quizzes?sort=total_users');
        $response->assertStatus(200);
        $quizIds = collect($response->json('data'))->pluck('id')->all();
        $this->assertEquals([$quizA->id, $quizC->id, $quizB->id], $quizIds);

        $response = $this->getJson('/api/quizzes?sort=-total_users');
        $response->assertStatus(200);
        $quizIds = collect($response->json('data'))->pluck('id')->all();
        $this->assertEquals([$quizB->id, $quizC->id, $quizA->id], $quizIds);
    }

    public function test_can_sort_quizzes_by_title()
    {
        $quizA = Quiz::factory()->create(['title' => 'Alpha']);
        $quizB = Quiz::factory()->create(['title' => 'Charlie']);
        $quizC = Quiz::factory()->create(['title' => 'Bravo']);

        $response = $this->getJson('/api/quizzes?sort=title');
        $response->assertStatus(200);
        $quizIds = collect($response->json('data'))->pluck('id')->all();
        $this->assertEquals([$quizA->id, $quizC->id, $quizB->id], $quizIds);

        $response = $this->getJson('/api/quizzes?sort=-title');
        $response->assertStatus(200);
        $quizIds = collect($response->json('data'))->pluck('id')->all();
        $this->assertEquals([$quizB->id, $quizC->id, $quizA->id], $quizIds);
    }

    public function test_can_filter_quizzes_by_categories_and_difficulty()
    {
        $categoryA = Category::factory()->create();
        $categoryB = Category::factory()->create();
        $difficulty = Difficulty::factory()->create();

        $quizA = Quiz::factory()->create(['difficulty_id' => $difficulty->id]);
        $quizB = Quiz::factory()->create(['difficulty_id' => $difficulty->id]);
        $quizC = Quiz::factory()->create(['difficulty_id' => $difficulty->id]);
        $quizD = Quiz::factory()->create();
        $quizD->categories()->detach();

        $quizA->categories()->attach($categoryA);
        $quizB->categories()->attach($categoryB);
        $quizC->categories()->detach();

        $response = $this->getJson('/api/quizzes?filter[categories.id]=' . $categoryA->id . ',' . $categoryB->id . '&filter[difficulty_id]=' . $difficulty->id);
        $response->assertStatus(200);

        $data = $response->json('data');
        $quizIds = collect($data)->pluck('id')->all();

        $this->assertContains($quizA->id, $quizIds);
        $this->assertContains($quizB->id, $quizIds);
        $this->assertNotContains($quizC->id, $quizIds);
        $this->assertNotContains($quizD->id, $quizIds);
    }

    public function test_can_filter_quizzes_by_title_and_categories()
    {
        $categoryA = Category::factory()->create();
        $categoryB = Category::factory()->create();

        $quizA = Quiz::factory()->create(['title' => 'Alpha']);
        $quizB = Quiz::factory()->create(['title' => 'Bravo']);
        $quizC = Quiz::factory()->create(['title' => 'Alpha']);
        $quizD = Quiz::factory()->create(['title' => 'Charlie']);
        $quizD->categories()->detach();

        $quizA->categories()->attach($categoryA);
        $quizB->categories()->attach($categoryB);
        $quizC->categories()->detach();

        $response = $this->getJson('/api/quizzes?filter[title]=Alpha&filter[categories.id]=' . $categoryA->id . ',' . $categoryB->id);
        $response->assertStatus(200);
        $quizIds = collect($response->json('data'))->pluck('id')->all();
        $this->assertContains($quizA->id, $quizIds);
        $this->assertNotContains($quizB->id, $quizIds);
        $this->assertNotContains($quizC->id, $quizIds);
        $this->assertNotContains($quizD->id, $quizIds);
    }

    public function test_can_filter_quizzes_by_title_and_difficulty()
    {
        $difficultyA = Difficulty::factory()->create();
        $difficultyB = Difficulty::factory()->create();

        $quizA = Quiz::factory()->create(['title' => 'Alpha', 'difficulty_id' => $difficultyA->id]);
        $quizB = Quiz::factory()->create(['title' => 'Bravo', 'difficulty_id' => $difficultyA->id]);
        $quizC = Quiz::factory()->create(['title' => 'Alpha', 'difficulty_id' => $difficultyB->id]);
        $quizD = Quiz::factory()->create(['title' => 'Charlie', 'difficulty_id' => $difficultyB->id]);

        $response = $this->getJson('/api/quizzes?filter[title]=Alpha&filter[difficulty_id]=' . $difficultyA->id);
        $response->assertStatus(200);
        $quizIds = collect($response->json('data'))->pluck('id')->all();
        $this->assertContains($quizA->id, $quizIds);
        $this->assertNotContains($quizB->id, $quizIds);
        $this->assertNotContains($quizC->id, $quizIds);
        $this->assertNotContains($quizD->id, $quizIds);
    }

    public function test_can_filter_quizzes_with_all_filters_combined()
    {
        $user = User::factory()->create();
        $categoryA = Category::factory()->create();
        $categoryB = Category::factory()->create();
        $difficultyA = Difficulty::factory()->create();
        $difficultyB = Difficulty::factory()->create();

        $quizMatch = Quiz::factory()->create([
            'title'         => 'Alpha',
            'difficulty_id' => $difficultyA->id,
        ]);
        $quizMatch->categories()->attach($categoryA);
        $user->quizzes()->attach($quizMatch->id, [
            'points'        => 10,
            'complete_time' => 100,
            'created_at'    => now(),
        ]);

        $quizWrongTitle = Quiz::factory()->create([
            'title'         => 'Bravo',
            'difficulty_id' => $difficultyA->id,
        ]);
        $quizWrongTitle->categories()->attach($categoryA);
        $user->quizzes()->attach($quizWrongTitle->id, [
            'points'        => 10,
            'complete_time' => 100,
            'created_at'    => now(),
        ]);

        $quizWrongCategory = Quiz::factory()->create([
            'title'         => 'Alpha',
            'difficulty_id' => $difficultyA->id,
        ]);
        $quizWrongCategory->categories()->attach($categoryB);
        $user->quizzes()->attach($quizWrongCategory->id, [
            'points'        => 10,
            'complete_time' => 100,
            'created_at'    => now(),
        ]);

        $quizWrongDifficulty = Quiz::factory()->create([
            'title'         => 'Alpha',
            'difficulty_id' => $difficultyB->id,
        ]);
        $quizWrongDifficulty->categories()->attach($categoryA);
        $user->quizzes()->attach($quizWrongDifficulty->id, [
            'points'        => 10,
            'complete_time' => 100,
            'created_at'    => now(),
        ]);

        $quizNotCompleted = Quiz::factory()->create([
            'title'         => 'Alpha',
            'difficulty_id' => $difficultyA->id,
        ]);
        $quizNotCompleted->categories()->attach($categoryA);

        $this->actingAs($user);
        $url = '/api/quizzes?filter[my_quizzes]=true&filter[categories.id]=' . $categoryA->id . '&filter[title]=Alpha&filter[difficulty_id]=' . $difficultyA->id;
        $response = $this->getJson($url);
        $response->assertStatus(200);
        $quizIds = collect($response->json('data'))->pluck('id')->all();
        $this->assertEquals([$quizMatch->id], $quizIds);
    }
}
