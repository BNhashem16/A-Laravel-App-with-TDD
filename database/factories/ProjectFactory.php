<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'owner_id' => User::factory()->create()->id,
            'notes' => $this->faker->paragraph,
        ];
    }

    public function createTasks(int $count = 1)
    {
        return $this->afterCreating(function (Project $project) use ($count) {
            Task::factory()->count($count)->create([
                'project_id' => $project->id,
            ]);
        });
    }

    public function ownedBy(User $user)
    {
        return $this->state(fn () => [
            'owner_id' => $user->id,
        ]);
    }

    public function withTasks(int $count = 1)
    {
        return $this->hasTasks($count);
    }
}
