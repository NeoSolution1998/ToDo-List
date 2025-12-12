<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_task()
    {
        $data = [
            'title' => 'Test task',
            'description' => 'Test description',
            'status' => 'pending',
        ];

        $this->postJson('/api/tasks', $data)
            ->assertStatus(201)
            ->assertJsonFragment([
                'title' => 'Test task',
                'description' => 'Test description',
                'status' => 'pending',
            ]);
    }

    public function test_title_is_required()
    {
        $data = [
            'description' => 'No title',
            'status' => 'pending',
        ];

        $this->postJson('/api/tasks', $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    public function test_can_get_tasks_list()
    {
        Task::factory()->count(3)->create();

        $this->getJson('/api/tasks')
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_get_single_task()
    {
        $task = Task::factory()->create();

        $this->getJson("/api/tasks/{$task->id}")
            ->assertStatus(200)
            ->assertJsonFragment(['title' => $task->title]);
    }

    public function test_can_update_task()
    {
        $task = Task::factory()->create([
            'title' => 'Old title',
            'status' => 'pending',
        ]);

        $data = [
            'title' => 'Updated title',
            'status' => 'done',
        ];

        $this->putJson("/api/tasks/{$task->id}", $data)
            ->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'Updated title',
                'status' => 'done',
            ]);
    }

    public function test_can_delete_task()
    {
        $task = Task::factory()->create();

        $this->deleteJson("/api/tasks/{$task->id}")
            ->assertStatus(200)
            ->assertJson(['message' => 'Task deleted']);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_delete_non_existing_task_returns_404()
    {
        $this->deleteJson('/api/tasks/999')
            ->assertStatus(404);
    }

    public function test_invalid_status_fails_validation()
    {
        $data = [
            'title' => 'Test invalid status',
            'status' => 'invalid_status',
        ];

        $this->postJson('/api/tasks', $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors('status');
    }
}
