<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_project()
    {
        $task = Task::factory()->create();
        $this->assertInstanceOf(Project::class, $task->project);
    }

    /** @test */
    public function it_has_path()
    {
        $task = Task::factory()->create();
        $route = route('projects.tasks.show', ['project' => $task->project, 'task' => $task]);
        $this->assertEquals($route, $task->path());
        
    }

}
