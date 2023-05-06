<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::factory()->create());


        $atteributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'owner_id' => auth()->id()
        ];

        $this->post('/projects', $atteributes);
        $this->assertDatabaseHas('projects', $atteributes);
        $this->get('/projects')->assertSee($atteributes['title']);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->actingAs(User::factory()->create());
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


}
