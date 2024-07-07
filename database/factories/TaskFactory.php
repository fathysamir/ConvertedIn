<?php
namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Task::class;
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['not started','working on it','stuck','on hold','under review','rejected','done']),
            'assigned_by_id' => $this->faker->numberBetween(1, 10), // Example: Replace with actual logic to assign by user ID
            'project_id' => $this->faker->numberBetween(1, 5), // Example: Replace with actual logic to assign project ID
            // Add more attributes as needed
        ];
    }
}