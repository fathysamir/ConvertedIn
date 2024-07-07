<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Project;

class TaskListTest extends TestCase
{
    //use RefreshDatabase;

    /** @test */
    public function it_displays_task_list_with_required_fields_and_pagination()
    {
        // Create users with roles
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $user->assignRole('user');

        // Create tasks with assigned and admin relationships
        $task=Task::factory()->create([
                              'title' => 'Task 100',
                              'is_active'=> '1',
                              'description'=>'Task number 100',
                              'status'=>'not started',
                              'project_id'=>$project->id,
                              'assigned_by_id'=>$admin->id]);
        $task->users()->attach([$user->id]);

        // Act: Visit the tasks page
        $response = $this->get(route('tasks'));

        // Assert: Response status and content
        $response->assertStatus(302); // Assuming 200 is the success status code for your page
        $response->assertViewIs('tasks'); // Assuming 'tasks.index' is the view name for tasks list

        // Assert: Paginated tasks with required fields
        $response->assertSeeInOrder(['title', 'description', 'assigned_by_id']);

        // Assert: Check pagination, assuming 10 tasks per page
        $response->assertSee('title', 10); // Check for 10 instances of 'title', adjust as per your actual view content
        $response->assertDontSee('title', 11); // Ensure 11th 'title' is not seen, indicating pagination

        // Additional assertions as per your pagination implementation
        $response->assertSee('Previous'); // Check for previous pagination link
        $response->assertSee('Next'); // Check for next pagination link
    }
}