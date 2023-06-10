<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['index', 'create', 'store', 'show']);
    }

    public function index()
    {
        $projects = Auth::user()->accessibleProjects();

        return view('projects.index', compact('projects'));
    }
    
    public function create()
    {
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request)
    {
        Auth::user()->projects()->create($request->validated());
        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);

        return view('projects.show', ['project' => $project]);
    }
    
    public function edit(Project $project)
    {
        return view('projects.edit', ['project' => $project]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        session()->flash('success', 'Project updated successfully');
        
        return redirect()->route('projects.show', ['project' => $project->id]);
    }
    
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        session()->flash('success', 'Project deleted successfully');
        
        return redirect()->route('projects.index');
    }
}
