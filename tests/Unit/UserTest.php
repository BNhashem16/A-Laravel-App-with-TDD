<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_user_has_project()
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    /** @test */
    public function a_user_has_accessible_projects()
    {
        $this->withoutExceptionHandling();
        $john = $this->signIn();
        $project = Project::factory()->create([
            'owner_id' => $john->id
        ]);
        $sally = User::factory()->create();
        $nick = User::factory()->create();
        $project->invite($sally);
        $project->invite($nick);
        $this->assertTrue($john->accessibleProjects()->contains($project));
        $this->assertTrue($sally->accessibleProjects()->contains($project));
        $this->assertTrue($nick->accessibleProjects()->contains($project));
        
    }
    
    /** @test */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        $this->withoutExceptionHandling();
        $john = $this->signIn();
        $project = Project::factory()->create();
        $project->invite($john);
        $this->get(route('projects.index'))->assertSee($project->title);
    }
}
