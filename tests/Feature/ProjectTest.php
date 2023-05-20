<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;


    /** @test */
    public function guests_cannot_create_projects()
    {
        $atteributes = Project::factory()->create();
        $atteributes = $atteributes->toArray();
        $response = $this->post('/projects', $atteributes);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guests_cannot_view_projects()
    {
        $response = $this->get(route('projects.index'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guests_cannot_view_a_single_projects()
    {
        $project = Project::factory()->create();
        $response = $this->get(route('projects.show', $project->id));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $this->get(route('projects.create'))->assertStatus(200);
    }

    /** @test */
    public function a_user_can_store_a_project()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $atteributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'owner_id' => auth()->id(),
            'notes' => $this->faker->paragraph,
        ];

        $this->post(route('projects.store'), $atteributes);
        $project = Project::where($atteributes)->first();
        $this->assertDatabaseHas('projects', $atteributes);
        $this->get(route('projects.show', ['project' => $project]))->assertSee($atteributes['title']);
        $this->isInstanceOf(Project::class, $project);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $data = ['title' => 'Changed title', 'description' => 'Changed description', 'notes' => 'Changed notes'];
        $project = auth()->user()->projects()->create($data);
        $route = route('projects.update', ['project' => $project->id]);
        $updateProject = $this->patch($route, $data)->assertRedirect($project->path());
        $this->assertDatabaseHas('projects', $data);
        $this->isInstanceOf(Project::class, $project);
        $this->assertEquals($data['title'], $project->title);
        $this->get(route('projects.edit', ['project' => $project->id]))->assertOk()->assertSee($data['title']);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->create();
        $route = route('projects.update', ['project' => $project->id]);
        $data = ['title' => 'Changed', 'description' => 'Changed', 'notes' => 'Changed'];
        $this->patch($route, $data)->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertDatabaseMissing('projects', $data);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();
        $atteributes = Project::factory()->create(['title' => '']);
        $atteributes = $atteributes->toArray();
        $response = $this->post('/projects', $atteributes);
        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);

        $atteributes = Project::factory()->create(['owner_id' => $user->id]);
        $this->get(route('projects.show', $atteributes->id))
        ->assertSee($atteributes->title)
        ->assertSee($atteributes->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create();
        $this->get(route('projects.show', $project->id))
        ->assertStatus(403);
    }


}
