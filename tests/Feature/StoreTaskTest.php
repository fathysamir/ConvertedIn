<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StoreTaskTest extends TestCase
{
    //use RefreshDatabase;

    // protected function setUp(): void
    // {
    //     parent::setUp();

    //     // Setup roles
    //     Role::create(['name' => 'user']);
    //     Role::create(['name' => 'admin']);
    // }

    /** @test */
    public function it_can_create_a_task()
    {
        // Create a project
        $project = Project::factory()->create();

        // Create an admin user
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create some users
        $users = User::factory()->count(3)->create()->each(function ($user) {
            $user->assignRole('user');
        });

        // Acting as a user with proper permissions (if required)
        $user = User::factory()->create();
        $this->actingAs($admin);

        // Task data
        $taskData = [
            'title' => 'Test Task 1',
            'description' => 'This is a test task',
            'status' => 'not started',
            'project' => $project->id,
            'admin' => $admin->id,
            'users' => $users->pluck('id')->toArray(),
            'is_active' => "on",
        ];

        // Make a POST request to the store route
        $response = $this->post(route('create.task'), $taskData);

        // Assert the task is created in the database
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task 1',
            'description' => 'This is a test task',
            'status' => 'not started',
            'project_id' => $project->id,
            'assigned_by_id' => $admin->id,
            'is_active' => '1',
        ]);

        // Assert the users are attached to the task
        $task = Task::where('title', 'Test Task 1')->first();
        foreach ($users as $user) {
            $this->assertDatabaseHas('tasks_users', [
                'task_id' => $task->id,
                'assigned_to_id' => $user->id,
            ]);
        }

        // Assert the response status and redirect
        $response->assertStatus(302);
        $response->assertRedirect(route('tasks'));
        $response->assertSessionHas('success', 'Task created successfully.');
    }
}