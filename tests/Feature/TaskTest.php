<?php

namespace Tests\Feature;

use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_validation_rules(): void
    {
        $response = $this->postJson('/api/task');

        $response->assertJsonValidationErrors(['task_definition', 'status_id', 'user_id']);
    }

    public function test_task_can_be_created(): void
    {
        //Create User and Status
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $response = $this->postJson('/api/task', [
            'task_definition' => 'Do homework',
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);

        $response->assertCreated();
        $this->assertCount(1, Task::all());
    }

    public function test_tasks_can_be_retrieved(): void
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        Task::factory()->create([
            'task_definition' => 'Do homework',
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);

        $user = User::factory()->create();
        $status = Status::factory()->create(['status_code' => 'Complete']);

        Task::factory()->create([
            'task_definition' => 'Go to extra class',
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);

        $response = $this->getJson('/api/task');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure(['*' => ['task_definition', 'status_id', 'user_id']]);

        $this->assertCount(2, Task::all());
    }

    public function test_specific_task_can_be_retrieved(): void
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        Task::factory()->create([
            'task_definition' => 'Do homework',
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);

        $user = User::factory()->create();
        $status = Status::factory()->create(['status_code' => 'Complete']);

        $complete_task = Task::factory()->create([
            'task_definition' => 'Go to extra class',
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);

        $response = $this->getJson('/api/task/' . $complete_task->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['task_definition' => 'Go to extra class']);
    }

    public function test_task_can_be_updated(): void
    {
        $user = User::factory()->create();
        $status = Status::factory()->create(['status_code' => 'Complete']);

        $complete_task = Task::factory()->create([
            'task_definition' => 'Go to extra class',
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);

        $response = $this->putJson(
            '/api/task/' . $complete_task->id,
            ['task_definition' => 'Go to extra class on Mondays']
        );

        $response->assertStatus(200)
            ->assertJsonFragment(['task_definition' => 'Go to extra class on Mondays']);
    }

    public function test_task_can_be_deleted(): void 
    {
        User::factory()->create();
        Status::factory()->create();
        $task = Task::factory()->create();

        $this->assertCount(1, Task::all());

        $response = $this->deleteJson('/api/task/' . $task->id);

        $response->assertStatus(204);
        $this->assertCount(0, Task::all());
    }
}
