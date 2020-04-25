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
                        <th scope="col">Active/Inactive</th>
                        <th scope="col">Status</th>
                </tr>
                </thead>
                <tbody>
       
        @foreach ($sumbers as $sumber)
                <tr>
                        <td style="vertical-align:middle" value="{{$sumber->id}}">{{$sumber->id}}</td>
                        <td style="vertical-align:middle" value="{{$sumber->sumber_name}}">{{$sumber->sumber_name}}</td>
                        <td style="vertical-align:middle">
                             <div class="form-inline">
                                <form action="{{url("/sumber/active/".$sumber->id)}}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-success" {{ $sumber->is_active == 0 ? '' : 'disabled' }} >Active</button>
                                    <input type="hidden" name="page" value=""/>
                                </form>

                                <form action="{{url("/sumber/inactive/".$sumber->id)}}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-danger" {{ $sumber->is_active == 1 ? '' : 'disabled' }}>Inactive</button>
                                    <input type="hidden" name="page" value=""/>
                                </form>
                            </div>
                        </td>
                        <td style="vertical-align:middle" value="{{$sumber->is_active}}">
                            @if($sumber->is_active === 1)
                            <span class="hidden-xs label label-success">Active</span>
                            @elseif($sumber->is_active === 0)
                            <span class="hidden-xs label label-danger">Inactive</span>
                            @endif
                        </td>
                </tr> 
        @endforeach
        
                </tbody>
            </table>
            <div class="row">
            
            <div class="pull-right">
                <a class="btn btn-primary" href="{{url('/sumber/create')}}"> Create</a>
                <a class="btn btn-primary" href="{{url('/')}}"> Back</a>
            </div>
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