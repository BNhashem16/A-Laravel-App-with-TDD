<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvitationRequest;
use App\Models\Project;
use App\Models\User;

class InvitationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }
    
    public function store(StoreInvitationRequest $request, Project $project)
    {
        $user = User::whereEmail($request->email)->first();
        $project->invite($user);
        return redirect()->route('projects.show', $project);
    }

}
