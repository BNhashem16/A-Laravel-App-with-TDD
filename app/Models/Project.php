<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function path()
    {
        return route('projects.show', $this->id);
    }

    function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
