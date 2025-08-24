@extends('common.layout')
@section('title','- Home')

@section('content')
    <h1>This is an example of the Authorization Code flow</h1>
    <p><a
        style="background-color:green; color:white; text-decoration:none; border-radius: 5px; padding: .5em;"
        href="{{route('spotify.allow')}}">Authorize
        </a></p>
@endsection
