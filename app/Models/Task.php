<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function complete()
    {
        $this->update(['completed' => true]);
        
        $this->recordActivity('completed task');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);

        $this->recordActivity('incompleted task');
    }

    public function path()
    {
        return route('projects.tasks.show', ['project' => $this->project, 'task' => $this]);
    }
    
    public function recordActivity(string $activity)
    {
        return $this->activity()->create([
            'project_id' => $this->project->id,
            'description' => $activity
        ]);
    }
    
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    
}
