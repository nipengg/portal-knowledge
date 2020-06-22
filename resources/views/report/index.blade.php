@extends('layouts.masterr')

@section('title', 'Ask a question');

@section('content')
<head>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="/DataTables/datatables.css">
 
<script type="text/javascript" charset="utf8" src="/DataTables/datatables.js"></script>

    <style>
        @import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);
    /****** Style Star Rating Widget *****/

    .rating { 
     border: none;
     float: left;
     vertical-align: center;
    }

    .rating > input { display: none; } 
    .rating > label:before { 
        margin: 5px;
        font-size: 1.25em;
        font-family: FontAwesome;
        display: inline-block;
        content: "\f005";
    }

    .rating > .half:before { 
        content: "\f089";
        position: absolute;
    }

    .rating > label { 
        color: #ddd; 
        float: right; 
    }

    /***** CSS Magic to Highlight Stars on Hover *****/

    .rating > input:checked ~ label, /* show gold star when clicked */
    .rating:not(:checked) > label:hover, /* hover current star */
    .rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */

    .rating > input:checked + label:hover, /* hover current star when changing rating */
    .rating > input:checked ~ label:hover,
    .rating > label:hover ~ input:checked ~ label, /* lighten current selection */
    .rating > input:checked ~ label:hover ~ label { color: #FFED85;  } 


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


<div class="container" style="overflow-x: auto;">
    {{-- <div class="card">
        <div class="card-body"> --}}
            <table id="table_id" class="table no-wrap">
                <thead>
                <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Request Type</th>
                        <th scope="col">Title</th>
                        <th scope="col">Topic</th>
                        <th scope="col">Requestor Date</th>
                        <th scope="col">Requestor Name</th>
                        <th scope="col">Department</th>
                        <th scope="col">Closing Date</th>
                        <th scope="col">Total Workouts Days</th>
                        <th scope="col">Status</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Reference Data Upload</th>
                        <th scope="col">Reference Data</th>
                        <th scope="col">Additional Information User</th>
                        <th scope="col">Additional Information Admin</th>
                </tr>
                </thead>
                <tbody>
                    <a href="{{ url('/report/excel') }}" class="btn btn-success btn-rounded btn-fw">
                    <b>Export EXCEL</a></b>
                    <a href="{{ url('report/pdf') }}" class="btn btn-primary btn-rounded btn-fw">
                    <b> Export PDF</a></b>
        @foreach ($questions as $question)
                <tr>
                    <td style="vertical-align:middle" value="{{$question->id}}">{{$question->id}}</td> 
                    <td style="vertical-align:middle" value="{{$question->category_name}}">{{$question->category_name}}</td> 
                    <td style="vertical-align:middle" value="{{$question->question_title}}"><a href="{{url("/question/$question->id")}}">{{$question->question_title}}</a></td>
                    <td style="vertical-align:middle" value="">{{$question->topic}}</td> 
                    <td style="vertical-align:middle" value="{{$question->created_at}}">{{$question->created_at}}</td>
                    <td style="vertical-align:middle" value="{{$question->users}}"><a href="{{url('/profile/'.$question->user_id)}}">{{$question->users}}</td>
                    <td style="vertical-align:middle" value="{{$question->department}}">{{$question->department}}</td>
                    <td style="vertical-align:middle" value="">
                        @if($question->closed_at === null)
                        -
                        @else
                        {{$question->closed_at}}</td>
                        @endif
                    <td style="vertical-align:middle" value="">
                        @if($question->closed_at === null)
                        Not Finished Yet
                        @else
                        {{$question->days}} Day</td>
                        @endif
                    <td style="vertical-align:middle" value="{{$question->accepted_answer_id}}">
                        @if($question->accepted_answer_id === 0)
                        <span class="hidden-xs label label-warning">Open</span>
                        @elseif($question->accepted_answer_id === 1 || $question->accepted_answer_id === 5)
                        <span class="hidden-xs label label-success">Done</span>
                        @elseif($question->accepted_answer_id === 2)
                        <span class="hidden-xs label label-warning">Open pending</span>
                        @elseif($question->accepted_answer_id === 3)
                        <span class="hidden-xs label label-danger">Stop</span>
                        @elseif($question->accepted_answer_id === 4)
                        <span class="hidden-xs label label-danger">Stop pending</span>
                        @endif
                    </td>
                    <td style="vertical-align:middle" value="">
                        <div class="rating">
                          {{$question->post_rating}} &nbsp; <input type="radio" checked/><label title="Awesome - 5 stars"></label>
                        </div>
                    </td>
                    <td style="vertical-align:middle" value="">
                        @foreach($question->files as $file)
                        @if($file === null)
                        -
                        @else
                        <a href="{{URL::asset('/files/'.@$file)}}" download="{{ $file }}"><span>{{$file}}</span></a>
                        @endif
                        @endforeach
                    </td>
                    <td style="vertical-align:middle" value="">
                        @foreach($question->refrences as $refrence)
                        @if($refrence === null)
                        -
                        @else
                        {{$refrence}}
                        @endif
                        @endforeach
                    </td>
                    <td style="vertical-align:middle" value="">
                        @if($question->additional_information === null)
                        -
                        @else
                        {{$question->additional_information}}
                        @endif
                    </td>
                    <td style="vertical-align:middle" value="">
                        @if($question->additional_information_admin === null)
                        -
                        @else
                        {{$question->additional_information_admin}}
                        @endif
                    </td>
                </tr> 
        @endforeach
                </tbody>
            </table>

            {{-- </div> --}}
        {{-- </div> --}}
    </div>
    <script>
    $(document).ready( function () {
        $('#table_id').DataTable();
    } );
    var $  = require( 'jquery' );
    var dt = require( 'datatables.net' )(); 
    </script>
@endsection