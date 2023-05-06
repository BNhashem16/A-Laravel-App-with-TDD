<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Document</title>
</head>

<body>
    <h1>Create a Project</h1>

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
        </div>

</body>

</html>
