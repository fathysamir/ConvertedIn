<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;

class DeleteTaskTest extends TestCase
{
    //use RefreshDatabase;

    /** @test */
    public function it_can_delete_a_task()
    {
        // Create a task
        $task = Task::factory()->create();

        // Run the controller method to delete the task
        $response = $this->delete(route('delete.task', $task));

        // Assert that the task is deleted from the database
        $this->assertDeleted($task);

        // Assert the response status and redirect
        $response->assertStatus(302); // Redirect status code
        $response->assertRedirect(route('tasks')); // Redirect to tasks route

        // Assert session has success message
        $response->assertSessionHas('success', 'Task deleted successfully.');
    }
}