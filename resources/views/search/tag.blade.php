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

<h3>Find Tags</h3>
<div class="container">
    {{-- <div class="card">
        <div class="card-body"> --}}
            <table id="table_id" class="table">
                <thead>
                <tr>
                        <th scope="col">Tags</th>
                </tr>
                </thead>
                <tbody>
       
        @foreach ($tags as $tag)
                <tr>
                    <td style="vertical-align:middle" value="{{$tag->tag}}"><a href="{{url("/done/tag/$tag->tag")}}">#{{$tag->tag}}</a></td>
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