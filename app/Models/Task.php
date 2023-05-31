<?php

namespace App\Models;

use App\Models\Activity;
use App\Models\Project;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Task extends Model
{
    use HasFactory;
    use RecordsActivity;
    
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
        
        $this->recordActivity('completed_task');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);

        $this->recordActivity('incompleted_task');
    }

    public function path()
    {
        return route('projects.tasks.show', ['project' => $this->project, 'task' => $this]);
    }
    


    
}
