<?php

namespace Tests\Feature;

use App\Models\ArchivedTask;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArchivedTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_archived_task_can_be_created(): void
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $task = Task::factory()->create([
            'task_title' => 'Title',
            'task_definition' => 'Do homework',
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);

        $this->assertCount(1, Task::all());

        $task->delete(); //SoftDelete

        $this->deleteJson('/api/task/permanent/delete/' . $task->id); //Hard delete

        $this->assertCount(1, ArchivedTask::withTrashed()->get());
    }

    public function test_archived_task_can_be_retrieved(): void
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $first_task = Task::factory()->create([
            'task_title' => 'Title',
            'task_definition' => 'Do homework',
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);

        $second_task = Task::factory()->create([
            'task_title' => 'Title',
            'task_definition' => 'Go to town',
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);

        $this->assertCount(2, Task::all());

        $first_task->delete(); //SoftDelete
        $second_task->delete();

        $this->deleteJson('/api/task/permanent/delete/' . $first_task->id); //Hard delete
        $this->deleteJson('/api/task/permanent/delete/' . $second_task->id); //Hard delete

        $this->assertCount(2, ArchivedTask::withTrashed()->get());

        $response = $this->getJson('/api/archived-task');

        $this->assertEquals(2, count(ArchivedTask::withTrashed()->get()));
    }

    public function test_archived_status_can_be_restored(): void
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $task = Task::factory()->create([
            'task_title' => 'Title',
            'task_definition' => 'Do homework',
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);
        $this->assertCount(1, Status::all());

        $task->delete(); //SoftDelete
        $this->assertCount(0, Task::all());
        $this->assertCount(1, Task::withTrashed()->get());

        $this->deleteJson('/api/task/permanent/delete/' . $task->id); //Hard delete
        $this->assertCount(0, Task::withTrashed()->get());
        //dd($task->id);
        $response = $this->deleteJson('/api/archived-task/restore/' . $task->id);

        $response->assertCreated();

        $this->assertCount(1, Task::withTrashed()->get());
    }
}
