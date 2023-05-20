@extends('layouts.app')
@section('content')
    <p>{{ $project->title }}</p>
    <p>{{ $project->description }}</p>
    <div>
        @foreach ($project->tasks as $task)
            <div>
                <h4>{{ $task->body }}</h4>
            </div>
        @endforeach
    </div>

@endsection
