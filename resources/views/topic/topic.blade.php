@extends('layouts.masterr')

@section('title', 'Ask a question');

@section('content')
<head>

</head>

<div class="container">
    @if (sizeof($topics) == 0)
    <div class="jumbotron">
        <center>
            <h6>Topic has not been set yet</h6>
        </center>
    </div>
    <div class="form-group label-floating">  
        <a class="btn btn-primary" href="{{url('/topics/create')}}"> Create</a>
        <a class="btn btn-primary" href="{{url('/')}}"> Back</a>
    </div>
    @else
    <center>
        @foreach ($topics as $topic)
        <center>
            <h2>Current Topic is : {{$topic->topic}}</h2>
        </center>
        <div class="form-group label-floating">  
            <a class="btn btn-primary" href="{{url('/topics/update/'.$topic->id)}}"> Update</a>
            <a class="btn btn-primary" href="{{url('/')}}"> Back</a>
        </div>
        @endforeach
    </center>
@endif
</div>

@endsection