@extends('layouts.masterr')

@section('title', 'Laravel-based Q&A Forum');

@section('content')
    
@if((Session::get('is_approved') === 'active') or (Session::has('username') === false))
<h3>{{$articles->title}}  
    <span class="tags"> 
            <a href="{{url("/articles/approved/$articles->id")}}" class="tag"><span class="label label-success">Approve</span></a> 
    </span>
</h3>
Tags:<span class="tags">                     &nbsp;
    @foreach($article_tags as $tag)
        <a href="{{url("/tag/article/$tag")}}" class="tag"><span class="label label-info">#{{$tag}}</span></a>
    @endforeach
</span>

<span class="tags pull-right">
Theme:<span class="tags">                     &nbsp;
        <a href="" class="tag"><span class="label label-info">{{$themes->theme}}</span></a>
</span>
</span> 

<span class="tags pull-right">
    Sumber:                     &nbsp;
        @foreach ($article_sumbers as $sumber)
        <a href="{{url("/tag/article/sumber/$sumber")}}" class="tag"><span class="label label-info">{{$sumber}}</span></a> &nbsp;
        @endforeach
</span>

<hr/>
<span class="pull-left">
    Files:                     &nbsp;
    @foreach($article_files as $file)
    <a href="{{URL::asset('/files/'.@$file)}}" download="{{ $file }}" class="tag"><span class="label label-info">{{$file}}</span></a> 
    @endforeach
</span>
<br/>
<br/>
<div class="col-xs-10 post">
    {{$articles->content}}
</div>

@endif   

@stop
@section('sidebar')
    @include('layouts.sidebar')
    
@stop