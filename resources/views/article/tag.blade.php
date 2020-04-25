@extends('layouts.masterr')

@section('title', 'Laravel-based Q&A Forum');

@section('content')
    <script>
        $(document).ready(function () {
            $("#filter-picker").on('change', function(){
                window.location.href = "{{url('/tag/article/'.$id.'?filter=')}}" + $("#filter-picker").val();
            });
        });
    </script>
    
@if((Session::get('is_approved') === 'active') or (Session::has('username') === false))
<h3>Article tagged with: #{{$id}}</h3>
<div>
    <select class="selectpicker" id="filter-picker" style="background: white !important;">
        <option value="recent"   {{$filter === "recent" ? 'selected' : ''}} >Recent</option>
        <option value="trending" {{$filter === "trending" ? 'selected' : ''}} >Trending</option>
    </select>
    <!-- pagination controls -->
    <div class="pull-right">
        {{$articles->links()}}
    </div>
</div>

        @if (sizeof($articles) == 0)
            <div class="jumbotron">
                <center>
                    <h6>No questions found</h6>
                </center>
            </div>
        @endif
        @foreach ($articles as $article)
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-sm-8 col-xs-10">
                            <br>
                            <div class="card-title" style="font-size: 1.4em;">
                                <a href="{{url("/articles/view/$article->id")}}">{{$article->title}} </a>
                            </div>
                            <div class="card-description" style="font-size: .9em;">Wrote
                                by <a href="">{{$article->user}}</a>
                            </div>
                             <span class="tags">
                                 @foreach($article->sumbers as $sumber)<a href="{{url("/tag/article/sumber/$sumber")}}" class="tag"><span class="label label-info">{{$sumber}}</span></a>&nbsp;
                                 @endforeach
                            </span>
                            <span class="tags">
                                @foreach($article->tags as $tag)<a href="{{url("/tag/article/$tag")}}" class="tag"><span class="label label-info">#{{$tag}}</span></a>&nbsp;
                                @endforeach
                           </span>
                           <span class="tags">
                                <a href="{{url('/tag/article/theme/'.$article->theme)}}" class="tag"><span class="label label-info">Theme: {{$article->theme}}</span></a>
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