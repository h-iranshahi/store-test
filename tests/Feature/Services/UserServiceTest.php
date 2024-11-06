<?php

namespace Tests\Feature\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(UserService::class);
    }

    public function test_create_user()
    {
        $data = [
            'name' => 'Hasan',
            'email' => 'hasan@example.com',
            'password' => bcrypt('password123'),
        ];

        $user = $this->service->createUser($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'hasan@example.com']);
    }

    public function test_get_all_users()
    {
        User::factory()->count(5)->create();
        $users = $this->service->getAllUsers();

        $this->assertCount(5, $users);
    }

    public function test_get_user_by_id()
    {
        $user = User::factory()->create();
        $found = $this->service->getUserById($user->id);

        $this->assertEquals($user->id, $found->id);
    }

    public function test_update_user()
    {
        $user = User::factory()->create(['name' => 'Old Name']);
        $updated = $this->service->updateUser($user->id, ['name' => 'New Name']);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('users', ['name' => 'New Name']);
    }

    public function test_delete_user()
    {
        $user = User::factory()->create();
        $deleted = $this->service->deleteUser($user->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
