@extends('layouts.masterr')

@section('title', 'Laravel-based Q&A Forum');

@section('content')
<script>
    $(document).ready(function () {
        $("#filter-picker").on('change', function(){
            window.location.href = "{{url('/?filter=')}}" + $("#filter-picker").val();
        });
    });
</script>
    
@if((Session::get('is_approved') === 'active') or (Session::has('username') === false))
<h3>Chat</h3>

<div>
    <select class="selectpicker" id="filter-picker" style="background: white !important;">
        <option value="recent"   {{$filter === "recent" ? 'selected' : ''}} >Recent</option>
        <option value="trending" {{$filter === "trending" ? 'selected' : ''}} >Trending</option>
    </select>
    <!-- pagination controls -->
    {{-- <div class="pull-right">
        {{$articles->links()}}
    </div> --}}
</div>

        @if (sizeof($qas) == 0)
            <div class="jumbotron">
                <center>
                    <h6>No Chat Shared</h6>
                </center>
            </div>
        @endif
        @foreach ($qas as $qa)
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class=" col-xs-10">
                            <br/>
                            <div class="card-title" style="font-size: 1.4em;">
                                <a href="{{url("/qa/chat/$qa->id")}}">{{$qa->qa_title}} 
                                @if($qa->accepted_qa_id === 0)
                                    <span class="hidden-xs label label-warning">Open</span>
                                    <span class="visible-xs label label-warning" style="padding: .3em 0 .3em 0"><i class="fa fa-comments"></i></span>
                                @elseif($qa->accepted_qa_id === 1)
                                    <span class="hidden-xs label label-success">Done</span>
                                    <span class="visible-xs label label-warning" style="padding: .3em 0 .3em 0"><i class="fa fa-comments"></i></span>
                                @endif
                                </a>
                            </div>
                            <div class="card-description" style="font-size: .9em;">Chat Created
                                by <a href="">{{$qa->user}}</a> and <a href="">{{$qa->admin}}</a>
                            </div>
                            <span class="tags">
                                @foreach($qa->tags as $tag)<a href="" class="tag"><span class="label label-info">#{{$tag}}</span></a>&nbsp;
                                @endforeach
                                <br/>
                           </span>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>   
        @endforeach

@endif


@stop

@section('sidebar')
    @include('layouts.sidebar')
@stop