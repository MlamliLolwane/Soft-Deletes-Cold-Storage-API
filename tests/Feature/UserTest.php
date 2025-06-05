<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_rules(): void
    {
        //Test required fields
        $response = $this->postJson('/api/register');
        $response->assertJsonValidationErrors(['name', 'email', 'password']);

        //Test password confirmation
        $response = $this->postJson('/api/register', ['name' => 'Mlamli Lolwane', 
        'email' => 'mlamlilolwane@gmail.com', 'password' => 'Mlamli123']);

        $response->assertJsonValidationErrors(['password']);
    }

    public function test_user_can_be_created(): void
    {
        $response = $this->postJson('/api/register', ['name' => 'Mlamli Lolwane', 
        'email' => 'mlamlilolwane@gmail.com', 'password' => 'Mlamli123', 
        'password_confirmation' => 'Mlamli123']);

        $response->assertCreated();
        $this->assertCount(1, User::all());
    }
}
