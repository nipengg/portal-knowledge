@extends('layouts.masterr')

@section('title', 'Ask a question');

@section('content')
<head>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="/DataTables/datatables.css">
 
<script type="text/javascript" charset="utf8" src="/DataTables/datatables.js"></script>
    <style>
        .form-inline {
        display: -webkit-box;
        display: flex;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
          flex-flow: row wrap;
        -webkit-box-align: center;
          align-items: center;
}
.dropdown{
    display: inline-block;
}
    .btn {
        display: inline-block;
        font-weight: 400;
        color: #212529;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
          user-select: none;
        background-color: white;
        border: 1px solid transparent;
        padding: 0.375rem 1rem;
        font-size: 0.9rem;
        line-height: 1.6;
        border-radius: 0.25rem;
        -webkit-transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    </style>
</head>

    <div class="container">
        {{-- <div class="card">
            <div class="card-body"> --}}
                <table id="table_id" class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Department</th>
                        <th scope="col">Approve / Unapprove</th>
                        <th scope="col">Status</th>
                        <th scope="col">Registered At</th>
                        <th scope="col">Maintenance</th>
                    </tr>
                    </thead>
                    <tbody>
            @foreach ($users as $user)
                    <tr>
                        <td style="vertical-align:middle" value="{{$user->id}}">{{$user->id}}</td>
                        <td style="vertical-align:middle" value="{{$user->username}}">{{$user->username}}</td>
                        <td style="vertical-align:middle" value="{{$user->email}}">{{$user->email}}</td>
                        <td style="vertical-align:middle" value="{{$user->department}}">{{$user->department}}</td>
                        <td style="vertical-align:middle">
                            @if (!$user->is_admin)
                                @if($user->is_approved == 'sending')
                            <div class="form-inline">
                                <form action="{{url("/users/approved/".$user->id)}}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-success" >Approve</button>
                                    <input type="hidden" name="page" value=""/>
                                </form>

                                <form action="{{url("/users/reject/".$user->id)}}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-danger" >Reject</button>
                                    <input type="hidden" name="page" value=""/>
                                </form>
                            </div>
                                @elseif($user->is_approved == 'active' || $user->is_approved == 'inactive')
                                <div class="form-inline">
                                    <form action="{{url("/users/approved/".$user->id)}}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-success" {{ $user->is_approved == 'inactive' ? '' : 'disabled' }}>Active</button>
                                    <input type="hidden" name="page" value=""/>
                                    </form>

                                    <form action="{{url("/users/inactive/".$user->id)}}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-danger" {{ $user->is_approved == 'active' ? '' : 'disabled' }}>Inactive</button>
                                    <input type="hidden" name="page" value=""/>
                                    </form>
                            </div>
                            @elseif($user->is_approved == 'reject')
                            <form action="{{url("/users/approved/".$user->id)}}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn " >Approve</button>
                                <input type="hidden" name="page" value=""/>
                            </form>
                                @endif
                             @elseif($user->is_admin == 1 || $user->is_admin == 2 || $user->is_admin == 3)
                                @if($user->is_approved == 'active')
                                    <strong class="p-5">Admin</strong>
                                @else
                                <div class="form-inline">
                                    <form action="{{url("/users/approved/".$user->id)}}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-success" >Approve</button>
                                        <input type="hidden" name="page" value=""/>
                                    </form>
    
                                    <form action="{{url("/users/reject/".$user->id)}}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-danger" >Reject</button>
                                        <input type="hidden" name="page" value=""/>
                                    </form>
                                </div>
                                @endif
                             @endif
                        </td>
                        <td style="vertical-align:middle">
                            @if (!$user->is_admin)
                                @if($user->is_approved == 'active')
                                <span class="hidden-xs label label-success">Active</span>
                                @elseif($user->is_approved == 'sending')
                                    <span class="hidden-xs label label-warning">Sending</span>
                                @elseif($user->is_approved == 'reject')
                                <span class="hidden-xs label label-danger">Reject</span>
                                @elseif($user->is_approved == 'inactive')
                                <span class="hidden-xs label label-danger">Inactive</span>
                                @endif
                            @elseif($user->is_approved == 'sending' and $user->is_admin == 1)
                                <span class="hidden-xs label label-warning">Sending</span>
                            @elseif($user->is_approved == 'active' and $user->is_admin == 1)
                                    <strong class="p-5">Admin</strong>
                            @elseif($user->is_approved == 'active' and $user->is_admin == 3)
                                    <strong class="p-5">Leader Approved Admin</strong>
                            @elseif($user->is_approved == 'active' and $user->is_admin == 2)
                                    <strong class="p-5">Approved Admin</strong>
                            @endif
                        </td>

                        <td style="vertical-align:middle">
                        <span class="hidden-xs label label-success">{{$user->created_at}}</span>
                        </td>


                        <td style="vertical-align:middle">
                            {{-- @if (!$user->is_admin)
                            <div class="form-inline">
                                    <button type="submit" class="btn ">Delete</button>
                                    <input type="hidden" name="page" value=""/>
                            </div>
                             @else
                                    <strong class="p-5">Admin</strong>
                             @endif --}}
                             <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-decoration:none">
                                    Properties<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    @if(Session::get('is_admin') === 3 and ($user->is_admin === 1))
                                    <li><a href="{{url("/users/edit/".$user->id)}}"><span class=""></span> Edit</a></li>
                                    <li><a href="{{url("/users/delete/".$user->id)}}"><span class=""></span> Delete</a></li>
                                    <li><a href="{{url("/users/approved/admin/".$user->id)}}"><span class=""></span> Verify</a></li>
                                    @else
                                    <li><a href="{{url("/users/edit/".$user->id)}}"><span class=""></span> Edit</a></li>
                                    <li><a href="{{url("/users/delete/".$user->id)}}"><span class=""></span> Delete</a></li>
                                    @endif
                                </ul>
                            </li>
                        </td>
                    </tr> 
            @endforeach
            
                    </tbody>
                </table>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{url('/')}}"> Back</a>
                </div>
            </div>
        {{-- </div>
    </div> --}}
    <script>
        $(document).ready( function () {
        $('#table_id').DataTable();
    } );
    var $  = require( 'jquery' );
    var dt = require( 'datatables.net' )(); 
    </script>
@endsection