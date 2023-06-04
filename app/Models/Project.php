<?php

namespace App\Models;

use App\Models\Activity;
use App\Models\Task;
use App\Models\User;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Project extends Model
{
    use HasFactory;
    use RecordsActivity;

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

    public function invite(User $user)
    {
        return $this->members()->attach($user);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }


}
