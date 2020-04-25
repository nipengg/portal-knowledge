@extends('layouts.masterr')
  
@section('content')
   
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h3>Edit Department</h3>
        </div>
    </div>
</div>

<form action="{{url("/departments/edit/id/".$departments->department_id)}}" method="POST">
    {!! csrf_field() !!}
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group label-floating">
                <label class="control-label" for="security">Name</label>
                <input type="text" name="name" class="form-control" value="{{$departments->department_name}}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group label-floating">
                <label class="control-label" for="security">Description</label>
                <input type="text" name="description" class="form-control" value="{{$departments->description}}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn-block btn btn-primary">Submit</button>
        </div>
    </div>
</form>
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@stop