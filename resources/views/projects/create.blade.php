@extends('layouts.app')
@section('content')
    <h1>Create a Project</h1>
    <div>
        <form method="POST" action="{{ route('projects.store') }}">
            @csrf
            <div>
                <input type="text" name="title" placeholder="Project title" class="form-control">
            </div>
            <div>
                <textarea name="description" placeholder="Project description" class="form-control"></textarea>
            </div>
            <div>
                <button type="submit" class="form-control">Create Project</button>
                <a href="{{ route('projects.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
