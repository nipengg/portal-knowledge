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
            <h3>Edit User</h3>
        </div>
    </div>
</div>

<form action="{{url("/users/edit/id/".$users->id)}}" method="POST">
    {!! csrf_field() !!}
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group label-floating">
                <label class="control-label" for="security">Username</label>
                <input type="text" name="username" class="form-control" value="{{$users->username}}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group label-floating">
                <label class="control-label" for="security">Email</label>
                <input type="text" name="email" class="form-control" value="{{$users->email}}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group label-floating">
                <label class="control-label" for="department">Department</label>
                <select class="form-control" id="department" name="department" required="" value="">
                    @foreach($departments as $department)   
                      <option value="{{$department->department_id}}">{{$department->department_name}} - {{$department->description}}</option>
                 @endforeach
             </select>
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