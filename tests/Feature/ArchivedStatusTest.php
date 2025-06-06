<?php

namespace Tests\Feature;

use App\Models\ArchivedStatus;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArchivedStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_archived_status_can_be_created(): void
    {
        $pending_status = Status::factory()->create(['status_code' => 'Pendeng']);
        $this->assertCount(1, Status::all());

        $pending_status->delete(); //SoftDelete

        $this->deleteJson('/api/status/permanent/delete/' . $pending_status->id); //Hard delete

        $this->assertCount(1, ArchivedStatus::withTrashed()->get());
    }

    public function test_archived_status_can_be_retrieved(): void
    {
        $pending_status = Status::factory()->create(['status_code' => 'Pending']);
        $complete_status = Status::factory()->create(['status_code' => 'Complete']);

        $this->assertCount(2, Status::all());

        $pending_status->delete(); //SoftDelete
        $complete_status->delete();

        $this->deleteJson('/api/status/permanent/delete/' . $pending_status->id); //Hard delete
        $this->deleteJson('/api/status/permanent/delete/' . $complete_status->id); //Hard delete

        $this->assertCount(2, ArchivedStatus::withTrashed()->get());

        $response = $this->getJson('/api/archived-status');

        $response->assertJsonCount(2);
    }

    public function test_archived_status_can_be_restored(): void
    {
        $pending_status = Status::factory()->create(['status_code' => 'Pending']);
        $this->assertCount(1, Status::all());

        $pending_status->delete(); //SoftDelete
        $this->assertCount(0, Status::all());
        $this->assertCount(1, Status::withTrashed()->get());

        $this->deleteJson('/api/status/permanent/delete/' . $pending_status->id); //Hard delete
        $this->assertCount(0, Status::withTrashed()->get());
        
        $response = $this->deleteJson('/api/archived-status/restore/' .$pending_status->id);
        $response->assertCreated();

        $this->assertCount(1, Status::withTrashed()->get());
    }
}
