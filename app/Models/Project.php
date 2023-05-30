<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Project extends Model
{
    use HasFactory;

    public $old = [];

    protected $guarded = ['id'];

    public function path()
    {
        return route('projects.show', $this->id);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    public function addTask($body): Task
    {
        $task = $this->tasks()->create($body);
        return $task;
    }

    public function activity()
    {
        return $this->hasMany(Activity::class, 'project_id')->latest();
    }

    public function recordActivity(string $activity)
    {
        return $this->activity()->create([
            'description' => $activity,
            'changes' => $this->activityChanges($activity),
        ]);
    }

    public function activityChanges($description)
    {
        if ($description === 'updated') {
            return [
                'before' => Arr::except(array_diff($this->old, $this->getAttributes()), 'updated_at'),
                
                'after' => Arr::except($this->getChanges(), 'updated_at'),
            ];
        }
    }

}
