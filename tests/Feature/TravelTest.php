<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Travel;

class TravelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function usersProvider()
    {
        return [
            'admin' => ['traveling@api.com'],
            'editor' => ['johnvich@api.com'],
        ];
    }

    /**
     * @dataProvider usersProvider
     */
    public function test_travel_creation($email)
    {
        $user = User::where('email', $email)->first();

        $response = $this->actingAs($user)->postJson('/api/addTravel', [
            'isPublic' => true,
            'slug' => 'test-travel-' . $email,
            'name' => 'Test Travel',
            'description' => 'A wonderful journey',
            'numberOfDays' => 5,
            'moods' => ['adventure' => 80, 'relax' => 20],
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('api_travels', ['slug' => 'test-travel-' . $email]);
    }

    /**
     * @dataProvider usersProvider
     */
    public function test_travel_update($email)
    {
        // Assuming there is at least one travel entry from the seeders
        $travel = Travel::first();
        $user = User::where('email', $email)->first();

        $response = $this->actingAs($user)->patchJson("/api/updateTravel/{$travel->id}", [
            'name' => 'Updated Test Travel ' . $email,
            'description' => 'An updated wonderful journey',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('api_travels', [
            'id' => $travel->id,
            'name' => 'Updated Test Travel ' . $email,
        ]);
    }
}
