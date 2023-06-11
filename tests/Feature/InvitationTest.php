<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function non_owners_may_not_invite_users()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $assertInvitationForbidden = function () use ($user, $project) {
            $this->actingAs($user)->post(route('projects.invitations.store', $project))->assertStatus(403);
        };
        $assertInvitationForbidden();
        $project->invite($user);
        $assertInvitationForbidden();
    }
        
    
    /** @test */
    public function a_project_owner_can_invite_a_user()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $this->signIn($project->owner);
        $this->actingAs($project->owner)->post(route('projects.invitations.store', $project), [
            'email' => $user->email
        ])->assertRedirect(route('projects.show', $project));
        $this->assertTrue($project->members->contains($user));
    }

    /** @test */
    public function the_email_address_must_be_associated_with_a_valid_birdboard_account()
    {
        $project = Project::factory()->create();
        $this->actingAs($project->owner)->post(route('projects.invitations.store', $project), [
            'email' => ' not a user'
        ])->assertSessionHasErrors([
            'email' => 'The user you are inviting must have a Birdboard account.'
        ]);
    }
    
    /** @test */
    public function invited_users_may_update_project_details()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $taskData = ['body' => 'Foo task'];
        $project->invite($user);
        $this->signIn($user);
        $this->post(route('projects.tasks.store', $project), $taskData);
        $this->assertDatabaseHas('tasks', $taskData);
    }
}
