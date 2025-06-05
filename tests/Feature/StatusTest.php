<?php

namespace Tests\Feature;

use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StatusTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_status_validation_rules(): void
    {
        //Send empty request
        $response = $this->postJson('/api/status');

        $response->assertJsonValidationErrors(['status_code']);
    }

    public function test_status_can_be_created(): void
    {
        $response = $this->postJson('/api/status', ['status_code' => 'Pending']);

        $response->assertCreated();
        $this->assertCount(1, Status::all());
    }

    public function test_statuses_can_be_retrieved_from_database(): void
    {
        //Use factory to create & verify 2 statuses
        Status::factory()->create(['status_code' => 'Pending']);
        Status::factory()->create(['status_code' => 'Complete']);
        $this->assertCount(2, Status::all());

        $response = $this->getJson('/api/status');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure(['*' => ['status_code']]);
    }

    public function test_a_single_status_can_be_retrieved_from_database(): void
    {
        //Use factory to create & verify 2 statuses
        $pending_status = Status::factory()->create(['status_code' => 'Pending']);
        Status::factory()->create(['status_code' => 'Complete']);
        $this->assertCount(2, Status::all());

        $response = $this->getJson('/api/status/' . $pending_status->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['status_code' => 'Pending']);
    }

    public function test_status_can_be_updated(): void
    {
        $pending_status = Status::factory()->create(['status_code' => 'Pendeng']);
        $this->assertCount(1, Status::all());

        //Update status
        $response = $this->putJson('/api/status/' . $pending_status->id, ['status_code' => 'Pending']);

        $response->assertStatus(200)
            ->assertJsonFragment(['status_code' => 'Pending']);
    }

    public function test_status_can_be_deleted(): void
    {
        $pending_status = Status::factory()->create(['status_code' => 'Pendeng']);
        $this->assertCount(1, Status::all());

        //Delete status
        $response = $this->deleteJson('/api/status/' . $pending_status->id);

        $response->assertStatus(204);
        $this->assertCount(0, Status::all());
    }

    public function test_deleted_status_can_be_restored(): void
    {
        $pending_status = Status::factory()->create(['status_code' => 'Pendeng']);
        $this->assertCount(1, Status::all());

        $pending_status->delete();
        $this->assertCount(0, Status::all());

        //Restore status
        $response = $this->putJson('/api/status/restore/' . $pending_status->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['deleted_at' => null]);
        $this->assertCount(1, Status::all());
    }
}
