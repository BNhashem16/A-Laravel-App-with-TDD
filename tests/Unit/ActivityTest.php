<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_user()
    {
        $this->signIn();
        $project = Project::factory()->create([
            'owner_id' => auth()->id()
        ]);
        $this->assertInstanceOf(User::class, $project->activity->first()->user);
        $this->assertEquals(auth()->id(), $project->activity->first()->user->id);
    }

    /** @test */
    public function it_can_invite_user()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $project->invite($user);
        $this->assertTrue($project->members->contains($user));
    }

}
