<?php
namespace Tests\Feature\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserRepository();
    }

    public function test_create_user()
    {
        $data = [
            'name' => 'Hasan',
            'email' => 'hasan@example.com',
            'password' => bcrypt('password123'),
        ];

        $user = $this->repository->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'hasan@example.com']);
    }

    public function test_get_all_users()
    {
        User::factory()->count(3)->create();
        $users = $this->repository->all();

        $this->assertCount(3, $users);
    }

    public function test_find_user_by_id()
    {
        $user = User::factory()->create();
        $foundUser = $this->repository->find($user->id);

        $this->assertEquals($user->id, $foundUser->id);
    }

    public function test_update_user()
    {
        $user = User::factory()->create(['name' => 'Hasan']);
        $updated = $this->repository->update($user->id, ['name' => 'Nima']);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('users', ['name' => 'Nima']);
    }

    public function test_delete_user()
    {
        $user = User::factory()->create();
        $deleted = $this->repository->delete($user->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
