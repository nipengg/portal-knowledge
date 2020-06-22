@extends('layouts.masterr')

@section('title', 'Laravel-based Q&A Forum');

@section('content')
    <script>
        $(document).ready(function () {
            $("#filter-picker").on('change', function(){
                window.location.href = "{{url('/done/category/'.$category.'?filter=')}}" + $("#filter-picker").val();
            });
        });
    </script>
    
    @if((Session::get('is_approved') === 'active'))
    <h3>Answered Request with {{$category}}</h3>
    <div>
        <select class="selectpicker" id="filter-picker" style="background: white !important;">
            <option value="recent"   {{$filter === "recent" ? 'selected' : ''}} >Recent</option>
{{--            <option value="trending" {{$filter === "trending" ? 'selected' : ''}} >Trending</option>--}}
            {{-- <option value="open"     {{$filter === "open" ? 'selected' : ''}} >Open</option> --}}
            {{-- <option value="answered" {{$filter === "answered" ? 'selected' : ''}} >Answered</option> --}}
        </select>
        <!-- pagination controls -->
        <div class="pull-right">
            {{$questions->links()}}
        </div>
    </div>
    <div id="questions">
        @if (sizeof($questions) == 0)
            <div class="jumbotron">
                <center>
                    <h6>No questions found</h6>
                </center>
            </div>
        @endif
        @foreach ($questions as $question)
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-xs-2"  style="display: flex; justify-content: center; flex-direction: row">
                            <center>
                                <br/><span style="font-size: 1.7em">{{$question['votes']}}</span><br/>votes<br/><br/>
                                @if($question->accepted_answer_id == 0)
                                <span class="hidden-xs label label-warning">Open</span>
                                @elseif($question->accepted_answer_id == 1 || $question->accepted_answer_id === 5)
                                <span class="hidden-xs label label-success">Answered</span>
                                @else
                                <span class="hidden-xs label label-warning">Open pending</span>
                                @endif
                            </center>
                        </div>
                        <div class="col-sm-8 col-xs-10">
                            <br/>
                            <div class="card-title" style="font-size: 1.4em;">
                                <a href="{{url("/question/$question->id")}}">{{$question['question_title']}}</a>
                            </div>
                            <div class="card-description" style="font-size: 0.9em;">
                                {{$question['summary_question']}}
                            </div>
                            <div class="card-description" style="font-size: .9em;">Asked
                                <span data-time-format="time-ago" data-time-value="{{strtotime($question['created_at'])}}"></span>
                                by <a href="{{url('/profile/'.$question['asker'])}}">{{$question['asker']}}</a>

                            </div>
                            <br/>
                            <span class="tags">
                            @foreach($question['tags'] as $tag)<a href="{{url("/done/tag/$tag")}}" class="tag"><span class="label label-info">#{{$tag}}</span></a>&nbsp;
                            @endforeach
                            </span>
                            <span class="tags">
                                <a href="{{url("/done/category/".$question->category_name)}}" class="tag"><span class="label label-info">Category: {{$question['category_name']}}</span></a>
                            </span>
                            <span class="tags">
                                <a href="{{url('/done/question/theme/'.$question->theme)}}" class="tag"><span class="label label-info">Theme: {{$question->theme}}</span></a>
                            </span>
                        </div>
                        <div class="col-sm-2 hidden-xs"  style="height:7em; display: flex; justify-content: center; flex-direction: column;">
                            <div>
                                <table>
                                    {{--<tr>--}}
                                        {{--<td><span class="fa fa-group"></span></td>--}}
                                        {{--<td>&nbsp;</td>--}}
                                        {{--<td><span style="font-size: .75em">1,204 viewers</span></td>--}}
                                    {{--</tr>--}}
                                    <tr>
                                        <td><span class="fa fa-comments"></span></td>
                                        <td>&nbsp;</td>
                                        <td><span style="font-size: .75em">{{$question['answers']}} answers</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
        @endforeach

        {{-- @foreach ($answered as $question)
        <div class="card">
            <div class="content">
                <div class="row">
                    <div class="col-xs-2"  style="display: flex; justify-content: center; flex-direction: row">
                        <center>
                            <br/><span style="font-size: 1.7em">{{$question['votes']}}</span><br/>votes<br/><br/>
                            @if($question->accepted_answer_id === 0)
                                <span class="hidden-xs label label-warning">Open</span>
                                <span class="visible-xs label label-warning" style="padding: .3em 0 .3em 0"><i class="fa fa-comments"></i></span>
                            @elseif($question->accepted_answer_id === 1)
                                <span class="hidden-xs label label-success">Answered</span>
                                <span class="visible-xs label label-warning" style="padding: .3em 0 .3em 0"><i class="fa fa-comments"></i></span>
                            @elseif($question->accepted_answer_id === 2)
                                <span class="hidden-xs label label-warning">Answer pending</span>
                                <span class="visible-xs label label-warning" style="padding: .3em 0 .3em 0"><i class="fa fa-comments"></i></span>
                            @endif
                        </center>
                    </div>
                    <div class="col-sm-8 col-xs-10">
                        <br/>
                        <div class="card-title" style="font-size: 1.4em;">
                            <a href="{{url("/question/$question->id")}}">{{$question['question_title']}}</a>
                        </div>
                        <div class="card-description" style="font-size: 0.9em;">
                            {{$question['summary_question']}}
                        </div>
                        <div class="card-description" style="font-size: .9em;">Asked
                            <span data-time-format="time-ago" data-time-value="{{strtotime($question['created_at'])}}"></span>
                            by <a href="{{url('/profile/'.$question['asker'])}}">{{$question['asker']}}</a>

                        </div>
                        <br/>
                        <span class="tags">
                        @foreach($question['tags'] as $tag)<a href="{{url("/tag/$tag")}}" class="tag"><span class="label label-info">#{{$tag}}</span></a>&nbsp;
                        @endforeach
                        </span>
                        <span class="tags">
                            <a href="{{url("/question/category/".$question->category_name)}}" class="tag"><span class="label label-info">Category: {{$question['category_name']}}</span></a>
                        </span>
                        <span class="tags">
                            <a href="{{url('/tag/question/theme/'.$question['theme'])}}" class="tag"><span class="label label-info">Theme: {{$question['theme']}}</span></a>
                        </span>
                    </div>
                    <div class="col-sm-2 hidden-xs"  style="height:7em; display: flex; justify-content: center; flex-direction: column;">
                        <div>
                            <table>
                                <tr>
                                    <td><span class="fa fa-comments"></span></td>
                                    <td>&nbsp;</td>
                                    @if($question['answers'] === 1)
                                    <td><span style="font-size: .75em">{{$question['answers']}} Response</span></td>
                                    @else
                                    <td><span style="font-size: .75em">{{$question['answers']}} Responses</span></td>
                                    @endif
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        @endforeach --}}

        <!-- pagination controls -->
        <div class="pull-right">
            {{$questions->links()}}
        </div>
    </div>
    @elseif(Session::get('is_approved') === 'sending' || Session::get('is_approved') === 'reject' || Session::get('is_approved') === 'inactive')
    <br/>
    <div class="jumbotron">
        <center>
            <h6>Your account is not active</h6>
        </center>
    </div>
    @else
    <div id="questions">
        @if (sizeof($questions) == 0)
            <div class="jumbotron">
                <center>
                    <h6>No questions found</h6>
                </center>
            </div>
        @endif
        @foreach ($questions as $question)
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-xs-2"  style="display: flex; justify-content: center; flex-direction: row">
                            <center>
                                <br/><span style="font-size: 1.7em">{{$question['votes']}}</span><br/>votes<br/><br/>
                                <span class="hidden-xs label label-{{ $question->accepted_answer_id == 0 ? 'warning' : 'success' }}">{{ $question->accepted_answer_id == 0 ? 'Open' : 'Answered' }}</span>
                                <span class="visible-xs label label-{{ $question->accepted_answer_id == 0 ? 'warning' : 'success' }}" style="padding: .3em 0 .3em 0">{!! $question->accepted_answer_id == 0 ? '<i class="fa fa-comments"></i>' : '<i class="fa fa-check"></i>' !!}</span>
                            </center>
                        </div>
                        <div class="col-sm-8 col-xs-10">
                            <br/>
                            <div class="card-title" style="font-size: 1.4em;">
                                <a href="{{url("/question/$question->id")}}">{{$question['question_title']}}</a>
                            </div>
                            <div class="card-description" style="font-size: .9em;">Asked
                                <span data-time-format="time-ago" data-time-value="{{strtotime($question['created_at'])}}"></span>
                                by <a href="{{url('/profile/'.$question['asker'])}}">{{$question['asker']}}</a>

                            </div>
                            <br/>
                            <span class="tags">
                            @foreach($question['tags'] as $tag)<a href="{{url("/tag/$tag")}}" class="tag"><span class="label label-info">#{{$tag}}</span></a>&nbsp;
                            @endforeach
                            </span>
                            <span class="tags">
                                <a href="" class="tag"><span class="label label-info">Category: {{$question['category_name']}}</span></a>
                            </span>
                        </div>
                        <div class="col-sm-2 hidden-xs"  style="height:7em; display: flex; justify-content: center; flex-direction: column;">
                            <div>
                                <table>
                                    {{--<tr>--}}
                                        {{--<td><span class="fa fa-group"></span></td>--}}
                                        {{--<td>&nbsp;</td>--}}
                                        {{--<td><span style="font-size: .75em">1,204 viewers</span></td>--}}
                                    {{--</tr>--}}
                                    <tr>
                                        <td><span class="fa fa-comments"></span></td>
                                        <td>&nbsp;</td>
                                        <td><span style="font-size: .75em">{{$question['answers']}} answers</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
        @endforeach

        <!-- pagination controls -->
        <div class="pull-right">
            {{$questions->links()}}
        </div>
    </div>
    @endif
@stop

@section('sidebar')
    @include('layouts.sidebar')
@stop