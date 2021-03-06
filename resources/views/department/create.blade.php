@extends('layouts.masterr')
  
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h3>Add New Department</h3>
        </div>
    </div>
</div>
   
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
   
<form action="{{url("/departments/store")}}" method="POST" method="POST">
    {!! csrf_field() !!}
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group label-floating">
                <label class="control-label" for="security">Department Name</label>
                <input type="text" name="department" class="form-control" required>
            </div>
            <div class="form-group label-floating">
                <label class="control-label" for="first_post">Description</label>
                <textarea class="form-control" rows="7" name="description" required></textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a class="btn btn-primary " href="{{url('/departments')}}"> Back</a>
        </div>
    </div>
   
</form>
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@stop