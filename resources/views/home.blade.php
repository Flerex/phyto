@extends('layouts.master')

@section('content')
    <div class="columns is-centered">
        <div class="notification is-success column is-one-quarter has-text-centered">
            You are logged in as <strong>{{ Auth::user()->name }}</strong>!
        </div>
    </div>
@endsection
