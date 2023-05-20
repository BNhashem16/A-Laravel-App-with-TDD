<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProjectTaskTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {

        $project = Project::factory()->create();

        $this->post(route('projects.tasks.store', ['project' => $project->id]))
        ->assertRedirect('login')
        ->assertStatus(302);
    }

    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $project = auth()->user()->projects()->create(Project::factory()->raw());
        $attributes = ['body' => 'Test task'];
        $route = route('projects.tasks.store', ['project' => $project->id]);
        $this->post($route, $attributes);
        $this->assertDatabaseHas('tasks', $attributes);
        $this->get(route('projects.show', ['project' => $project]))->assertSee($attributes['body']);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();
        $project = Project::factory()->create();

        $this->post(route('projects.tasks.store', ['project' => $project->id]), ['body' => 'Test task'])
            ->assertStatus(403)
            ->assertForbidden();
        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $this->signIn();
        $project = auth()->user()->projects()->create(Project::factory()->raw());
        $attributes = ['body' => ''];
        $route = route('projects.tasks.store', ['project' => $project->id]);
        $this->post($route, $attributes)->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $project = auth()->user()->projects()->create(Project::factory()->raw());
        $data = [
            'body' => 'Test task',
            'completed' => true
        ];

        $task = $project->addTask(['body' => 'Test task']);
        $this->patch(route('projects.tasks.update', ['project' => $project->id, 'task' => $task->id]), $data);

        $this->assertDatabaseHas('tasks', $data);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $task = $project->addTask(['body' => 'Test task']);
        $this->patch(route('projects.tasks.update', ['project' => $project->id, 'task' => $task->id]), ['body' => 'Changed'])
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertForbidden();
        $this->assertDatabaseMissing('tasks', ['body' => 'Changed']);
    }

  

}
