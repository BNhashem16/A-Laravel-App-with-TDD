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
    public function a_project_can_invite_a_user()
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
