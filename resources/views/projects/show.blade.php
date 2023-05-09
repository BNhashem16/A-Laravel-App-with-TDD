@extends('layouts.app')
@section('content')
    <h1>{{ $project->title }}</h1>
    <p>{{ $project->description }}</p>
    <div>
        @foreach ($project->tasks as $task)
            <div>
                <h4>{{ $task->body }}</h4>
            </div>
        @endforeach
    </div>

    <a href="{{ route('projects.edit', $project) }}">Edit</a>
    <form method="POST" action="{{ route('projects.destroy', $project) }}">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
@endsection
