<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function creating_a_project()
    {
        $project = Project::factory()->create();
        $this->assertCount(1, $project->activity);
        $activity = $project->activity->last();

        $this->assertNull($activity->changes);
        $this->assertEquals('created', $activity->description);
    }

    /** @test */
    public function updating_a_project()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $originalTitle = $project->title;
        $project->update(['title' => 'changed']);
        $this->assertCount(2, $project->activity);

        $activity = $project->activity->last();
        $expected = [
            'before' => ['title' => $originalTitle],
            'after' => ['title' => 'changed']
        ];  
        $this->assertEquals('updated', $activity->description);
        $this->assertEquals($expected, $activity->changes);
        // $this->assertInstanceOf(Project::class, $activity->subject);
        // $this->assertEquals('updated', $project->activity->last()->description);
        // $this->assertInstanceOf(Activity::class, $project->activity->last());
    }

    /** @test */
    public function creating_a_new_task()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $project->addTask(['body' => 'Some task']);
        $this->assertCount(2, $project->activity);

        $activity = $project->activity->last();
        $this->assertEquals('created task', $activity->description);
        $this->assertInstanceOf(Task::class, $activity->subject);
        
    }

    /** @test */
    public function completing_a_new_task()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->withTasks()->create();
        $this->actingAs($project->owner)->patch($project->tasks->first()->path(), [
            'body' => 'foobar',
            'completed' => true
        ]);
        $this->assertCount(3, $project->activity);
        $this->assertEquals('completed task', $project->activity->last()->description);
        $this->assertInstanceOf(Activity::class, $project->activity->last());
    }

}
