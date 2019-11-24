@extends('panel.projects.partials.layout')

@section('project_content')
    {{-- FIXME: testing only --}}
    <ul>
        @foreach($project->users as $user)
            <li>{{ $user->name }}</li>
        @endforeach
    </ul>
@endsection
