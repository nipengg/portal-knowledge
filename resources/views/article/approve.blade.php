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
<span class="pull-left">
@if((Session::get('is_approved') === 'active') or (Session::has('username') === false))
<h3>Approval Article</h3>
<div>
    <select class="selectpicker" id="filter-picker" style="background: white !important;">
        <option value="recent"   {{$filter === "recent" ? 'selected' : ''}} >Recent</option>
    </select>
    <!-- pagination controls -->
    <div class="pull-right">
        {{$articles->links()}}
    </div>
</div>
</span>
<span class="pull-right">
    <div class="form-group label-floating">  
        <form action="/articles/search" method="GET">
            <label class="control-label" for="search">Search</label>
            <input class="form-control" type="text" name="search" value="" required/>
            <input type="submit" class="btn-block btn btn-primary" value="Search">
        </form>
    </div>	
</span>
<br/>
    <div class="col-xs-12">
        @if (sizeof($articles) == 0)
            <div class="jumbotron">
                <center>
                    <h6>No article found</h6>
                </center>
            </div>
        @endif

        @foreach ($articles as $article)
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class=" col-xs-10">
                            <br>
                            <div class="card-title" style="font-size: 1.4em;">
                                <a href="{{url("/articles/approve/view/$article->id")}}">{{$article->title}} </a>
                                <span class="hidden-xs label label-danger">Not Approved</span>
                                    <span class="visible-xs label label-warning" style="padding: .3em 0 .3em 0"><i class="fa fa-comments"></i></span>
                            </div>
                            <div class="card-description" style="font-size: .9em;">Wrote
                                by <a href="">{{$article->user}}</a>
                            </div>
                            <div class="card-description" style="font-size: .9em;">
                                 {{$article->summary}}
                            </div>
                             <span class="tags">
                                 @foreach($article->sumbers as $sumber)<a href="{{url("/tag/article/sumber/$sumber")}}" class="tag"><span class="label label-info">{{$sumber}}</span></a>&nbsp;
                                 @endforeach
                            </span>
                            <span class="tags">
                                <a href="{{url('/tag/article/theme/'.$article->theme)}}" class="tag"><span class="label label-info">Theme : {{$article->theme}}</span></a>&nbsp;
                           </span>
                            <span class="tags">
                                @foreach($article->tags as $tag)<a href="{{url("/tag/article/$tag")}}" class="tag"><span class="label label-info">#{{$tag}}</span></a>&nbsp;
                                @endforeach
                           </span>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
        @endforeach
    @endif
    </div>
    <div class="pull-right">
        {{$articles->links()}}
    </div>
@stop

@section('sidebar')
    @include('layouts.sidebar')
@stop