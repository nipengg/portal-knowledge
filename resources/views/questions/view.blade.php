<html>
@extends('layouts.master')

@section('title')
    {{$question->question_title}}
@stop

<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
@section('content')
    <style> 
    @import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);
    /****** Style Star Rating Widget *****/

    .rating { 
     border: none;
     float: left;
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


        .tags-picker{
            border: 0px solid black;

        }
        .post{
            font-size: 1.3em;
            padding: .6em;
        }
        .poster{
            font-size: .8em;
            color: gray;
            /*text-align: right;*/
            line-height: 1.5em;
        }
        .unvoted{
            color: lightgray;
            transition-duration: .3s;
        }
        .unvoted:hover{
            color: #333399;
            transition-duration: .3s;
        }
        .btn-accept{
            opacity: .5;
        }
        .btn-accept:hover{
            opacity: 1;
        }
        .btna {
        display: inline-block;
        font-weight: 400;
        color: white;
        text-align: center;
        vertical-align: middle;
        text-decoration: none;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
          user-select: none;
        background-color: #333399;
        border: 1px solid transparent;
        padding: 0.375rem 1rem;
        font-size: 0.9rem;
        line-height: 1.6;
        border-radius: 0.25rem;
        -webkit-transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        #file{
            position: relative;
            z-index: 2;
            float: left;
            width: 90%;
            margin-bottom: 0;
            border: 1;
            background-image: #D2D2D2;
            background-size: 0 2px, 100% 1px;
            background-repeat: no-repeat;
            background-position: center bottom, center calc(100% - 1px);
            background-color: transparent;
            transition: background 0s ease-out;
            box-shadow: none;
            border-radius: 0;
            font-weight: 400;
            height: 36px;
            padding: 7px 0;
            font-size: 14px;
            line-height: 1.42857;
        }
    </style>



    <script>
        $(document).ready(function(){
            $('#btn-custom').on('click', function(){
                if($('.tokens-container').children().length <= 2){
//                    console.log('token container no tags');
                    $('.tag-error').show().effect('shake');
                    $('.tokens-container').focus();
                    return false;
                } else {
                    $('.tag-error').hide();
                }
            });
        });
    </script>
    <div id="fb-root"></div>
    <h3>{{$question->question_title}}
        @if(Session::get('is_admin') === 0 and Session::get('id') === $question->user_id and $question->accepted_answer_id === 0)
        <span class="tags">
                <a href="{{url("/question/edit/".$question->id)}}" class="tag"><span class="label label-warning"> Edit </span></a> 
        </span>
        @else
        
        @endif
    </h3>
    Tags:<span class="tags">                     &nbsp;
        @foreach($question_tags as $tag)
            <a href="{{url("/tag/$tag")}}" class="tag"><span class="label label-info">#{{$tag}}</span></a>
        @endforeach
    </span>
    @if(Session::get('is_admin') === 1 || Session::get('is_admin') === 2 || Session::get('is_admin') === 3)
        
    <span class="tags pull-right">                     &nbsp;
        @if(($question->accepted_answer_id === 1 || $question->accepted_answer_id === 5) and $question->category_name === 'LEFO')
            @if($question->meeting === null)
            <a  onclick="$('#meeting').modal('show');" class="tag"><span class="label label-info">Set Meeting Date</span></a> 
            @else
            Meeting:
            @foreach ($category as $item)
            <a  onclick="$('#meeting').modal('show');" class="tag"><span class="label label-info">{{$item->meeting}}</span></a> 
            @endforeach
            @endif
        @endif
    </span>
    @elseif(Session::get('is_admin') === 0)
        @if(($question->accepted_answer_id === 1 || $question->accepted_answer_id === 5) and $question->category_name === 'LEFO')
            @if($question->meeting === null)
             <span class="tags pull-right">
                 Meeting:
                 <a class="tag"><span class="label label-warning">Not Set</span></a>
             </span>
            @else
             <span class="tags pull-right">
                Meeting:                     &nbsp;
                    @foreach ($category as $item)
                        <a class="tag"><span class="label label-info">{{$item->meeting}}</span></a> 
                     @endforeach
            </span>
             @endif
    @else

    @endif

        @endif
    <span class="tags pull-right">
        Category:                     &nbsp;
            @foreach ($category as $item)
            <a href="" class="tag"><span class="label label-info">{{$item->category_name}}</span></a> &nbsp;
            @endforeach
    </span>
    <span class="tags pull-right">
        Theme:                     &nbsp;
            <a href="" class="tag"><span class="label label-info">{{$themes->theme}}</span></a> &nbsp;
    </span>
    <br/>
    <div class="row" id="{{$answers[0]->id}}">
        <div class="col-xs-12">
            <div class="col-xs-10 post">
                {!! nl2br($first_post->post_content) !!}
            </div>

            <div class="col-xs-2">
                <center>
                    &nbsp;<a href="
                            {{ (!$answers[0]->voted) ? url("/vote/".$answers[0]->id) : url("/unvote/".$answers[0]->id)}}"
                            {!! ($answers[0]->voted) ? 'data-toggle="tooltip" data-placement="top" title="You already voted for this post"' : '' !!}><span class="
                             @if (!$answers[0]->voted)
                                unvoted
                            @endif
                            fa fa-3x fa-caret-up"></span></a>
                    <br/><span style="font-size: 1.7em">{{$first_post->votes}}</span><br/><span class="hidden-xs">votes<br/></span><br/>
                    @if($question->accepted_answer_id === 0)
                        <span class="hidden-xs label label-warning">Open</span>
                        <span class="visible-xs label label-warning" style="padding: .3em 0 .3em 0"><i class="fa fa-comments"></i></span>
                    @elseif($question->accepted_answer_id === 1 || $question->accepted_answer_id === 5)
                        <span class="hidden-xs label label-success">Answered</span>
                        <span class="visible-xs label label-warning" style="padding: .3em 0 .3em 0"><i class="fa fa-comments"></i></span>
                    @elseif($question->accepted_answer_id === 2)
                        <span class="hidden-xs label label-warning">Answer pending</span>
                        <span class="visible-xs label label-warning" style="padding: .3em 0 .3em 0"><i class="fa fa-comments"></i></span>
                    @elseif($question->accepted_answer_id === 3)
                        <span class="hidden-xs label label-danger">Stop</span>
                        <span class="visible-xs label label-warning" style="padding: .3em 0 .3em 0"><i class="fa fa-comments"></i></span>
                    @elseif($question->accepted_answer_id === 4)
                        <span class="hidden-xs label label-danger">Stop by admin</span>
                        <span class="visible-xs label label-warning" style="padding: .3em 0 .3em 0"><i class="fa fa-comments"></i></span>
                    @endif
                </center>
                <br/>
            </div>  
        </div>
        
        {{--<div class="col-xs-12">--}}
            {{--<!-- comments here -->--}}

            {{--<!-- form to add comments here -->--}}
            {{--<form action="{{url('/comment')}}">--}}
                {{--<input type="hidden" name="_token" value="{{ csrf_token() }}"/>--}}
                {{--<input type="hidden" name="post_id" value="{{$first_post->id}}"/>--}}
                {{--<input type="text" class="form-control" placeholder="Comment here... (max 200 characters)" />--}}
            {{--</form>--}}
        {{--</div>--}}
    </div>
    <hr/>
    {{-- @if($question->accepted_answer_id == 1)
        <h3>Accepted answer <i class="fa fa-check" style="color: rgb(76, 175, 80);"></i></h3>
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-10 post">
                    {!! nl2br($question->post_content) !!}
                </div>
                <div class="col-xs-2">
                    <center>
                        &nbsp;<a href="
                                {{ (!$question->voted) ? url("/vote/$accepted_answer->id") : url("/unvote/".$accepted_answer->id)}}"
                                {!! ($question_answer->voted) ? 'data-toggle="tooltip" data-placement="top" title="You already voted for this post"' : '' !!}><span class="
                                 @if (!$accepted_answer->voted)
                                    unvoted
                                @endif
                                    fa fa-3x fa-caret-up"></span></a>
                        <br/><span style="font-size: 1.7em">{{$accepted_answer->votes}}</span><br/><span class="hidden-xs">votes<br/></span><br/>
                        @if (Session::get('id') === $first_post->user_id && $question->accepted_answer_id === 0)
                            <form action="{{url("/question/accept-answer")}}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="question_id" value="{{$question->id}}">
                                <input type="hidden" name="post_id" value="{{$accepted_answer->id}}">
                                <button class="btn btn-success btn-sm btn-round"><span class="fa fa-check-box"></span><span class="hidden-xs"> Accept</span></button>
                                
                            </form>
                        @endif
                        @if ($question->accepted_answer_id === $accepted_answer->id)
                            <span class="hidden-xs label label-sm label-success"><span class="fa fa-check"></span> Answer</span>
                            <span class="visible-xs label label-sm label-success" style="padding: .3em 0 .3em 0"><span class="fa fa-check"></span></span>
                        @endif
                    </center>
                    <br/>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-sm-12 poster">
                        answer given by
                        <a href="{{url("/profile/$accepted_answer->username")}}">
                            {{$accepted_answer->username}}</a> on <span data-time-format="time-ago" data-time-value="{{strtotime($accepted_answer->created_at)}}"></span>
                    </div>
                </div>
            </div>
        </div>
    <hr/>
    @endif --}}
    <h3>Answers ({{$answers->count()-1}})</h3>
    <hr/>

    
@if(Session::get('id') === $question->user_request_id and $question->accepted_answer_id === 0)
    <span class="tags pull-left">
        Estimate Time:                     &nbsp;
            @foreach ($category as $item)
            <a href="" class="tag"><span class="label label-info">{{$question->estimated_time}}</span></a> 
            @endforeach
            <a class="btna btn-primary" onclick="$('#estimate').modal('show');">Update Time</a>
    </span>

    @if($question->estimated_time !== $question->estimated_time_updated)
    <div class="col-sm-12 poster" style="padding-left : 0px">
        Estimated Time Updated by
        <a href="">
            {{$admins->username}}</a></span>
    </div>
    @else

    @endif
@endif

{{-- Rating --}}
<div class="form-group">
@if($question->accepted_answer_id === 1 || $question->accepted_answer_id === 5)
    @if($question->post_rating === 0.0 and Session::get('id') === $question->user_id)
<form action="{{url("/question/rating/".$question->id)}}">
<div class="col-sm-12 poster">
   <label class="control-label" for="rating">Rating</label>
</div>
<fieldset class="rating">
    <input type="radio" id="star5" name="rating" value="5.0" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
    <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
    <input type="radio" id="star4" name="rating" value="4.0" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
    <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
    <input type="radio" id="star3" name="rating" value="3.0" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
    <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
    <input type="radio" id="star2" name="rating" value="2.0" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
    <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
    <input type="radio" id="star1" name="rating" value="1.0" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
    <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
</fieldset>
<button class="btn btn-success btn-xs btn-round" style="padding: .5em"><span class="hidden-xs"> Set Rating</span></button>
<br/>
<br/>
</form>
    @elseif($question->post_rating === 5.0 and (Session::get('id') === $question->user_id || Session::get('id') === $question->user_request_id))
    <div class="col-sm-12 poster">
        <label class="control-label" for="rating">Awesome</label>
     </div>
    <fieldset class="rating" disabled>
        <input type="radio" id="star5" name="rating" value="5" checked /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
        <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
        <input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
        <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
        <input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
        <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
        <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
        <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
        <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
        <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
    </fieldset>
    @elseif($question->post_rating === 4.5)
    <div class="col-sm-12 poster">
        <label class="control-label" for="rating">Pretty Good</label>
     </div>
    <fieldset class="rating" disabled>
        <input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
        <input type="radio" id="star4half" name="rating" value="4.5" checked /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
        <input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
        <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
        <input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
        <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
        <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
        <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
        <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
        <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
    </fieldset>
    @elseif($question->post_rating === 4.0)
    <div class="col-sm-12 poster">
        <label class="control-label" for="rating">Pretty Good</label>
     </div>
    <fieldset class="rating" disabled>
        <input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
        <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
        <input type="radio" id="star4" name="rating" value="4" checked /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
        <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
        <input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
        <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
        <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
        <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
        <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
        <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
    </fieldset>
    @elseif($question->post_rating === 3.5)
    <div class="col-sm-12 poster">
        <label class="control-label" for="rating">Good</label>
     </div>
    <fieldset class="rating" disabled>
        <input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
        <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
        <input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
        <input type="radio" id="star3half" name="rating" value="3.5" checked /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
        <input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
        <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
        <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
        <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
        <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
        <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
    </fieldset>
    @elseif($question->post_rating === 3.0)
    <div class="col-sm-12 poster">
        <label class="control-label" for="rating">Good</label>
     </div>
    <fieldset class="rating" disabled>
        <input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
        <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
        <input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
        <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
        <input type="radio" id="star3" name="rating" value="3" checked /><label class = "full" for="star3" title="Meh - 3 stars"></label>
        <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
        <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
        <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
        <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
        <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
    </fieldset>
    @elseif($question->post_rating === 2.5)
    <div class="col-sm-12 poster">
        <label class="control-label" for="rating">Kinda Bad</label>
     </div>
    <fieldset class="rating" disabled>
        <input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
        <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
        <input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
        <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
        <input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
        <input type="radio" id="star2half" name="rating" value="2.5" checked /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
        <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
        <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
        <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
        <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
    </fieldset>
    @elseif($question->post_rating === 2.0)
    <div class="col-sm-12 poster">
        <label class="control-label" for="rating">Kinda Bad</label>
     </div>
    <fieldset class="rating" disabled>
        <input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
        <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
        <input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
        <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
        <input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
        <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
        <input type="radio" id="star2" name="rating" value="2" checked /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
        <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
        <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
        <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
    </fieldset>
    @elseif($question->post_rating === 1.5)
    <div class="col-sm-12 poster">
        <label class="control-label" for="rating">Bad</label>
     </div>
    <fieldset class="rating" disabled>
        <input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
        <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
        <input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
        <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
        <input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
        <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
        <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
        <input type="radio" id="star1half" name="rating" value="1.5" checked /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
        <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
        <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
    </fieldset>
    @elseif($question->post_rating === 1.0)
    <div class="col-sm-12 poster">
        <label class="control-label" for="rating">Bad</label>
     </div>
    <fieldset class="rating" disabled>
        <input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
        <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
        <input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
        <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
        <input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
        <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
        <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
        <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
        <input type="radio" id="star1" name="rating" value="1" checked /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
        <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
    </fieldset>
    @elseif($question->post_rating === 0.5)
    <div class="col-sm-12 poster">
        <label class="control-label" for="rating">Bad</label>
     </div>
    <fieldset class="rating" disabled>
        <input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
        <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
        <input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
        <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
        <input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
        <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
        <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
        <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
        <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
        <input type="radio" id="starhalf" name="rating" value="0.5" checked /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
    </fieldset>
    @else
    
    @endif
@endif
</div>
{{--  --}}
@if($question->accepted_answer_id === 2)
    <span class="hidden-xs label label-warning">Cause: {!! nl2br($issues->issue) !!}</span>
    <br/>
    <br/>
@elseif($question->accepted_answer_id === 4)
    <span class="hidden-xs label label-info">Stop Cause: {{$question->additional_information_admin}}</span>
    <br/>
    <br/>
@endif
    <div class="pull-right">
        {{$answers->links()}}
    </div>
    <div class="clearfix"></div>
    <div>
        @foreach($answers as $answer)
            @if ($answer->id == $first_post->id) @continue @endif <!-- not showing the first post twice -->
                @if(!$answer->decline_answer_id)
            <div class="row" id="{{$answer->id}}">
                <div class="col-xs-12">
                    <div class="col-xs-10 post">
                        {!! nl2br($answer->post_content) !!}
                    </div>
                    <div class="col-xs-2">
                        <center>
                            &nbsp;<a href="
                                    {{ (!$answer->voted) ? url("/vote/$answer->id") : url("/unvote/".$answer->id)}}"
                                    {!! ($answer->voted) ? 'data-toggle="tooltip" data-placement="top" title="You already voted for this post"' : '' !!}><span class="
                                     @if (!$answer->voted)
                                        unvoted
                                    @endif
                                    fa fa-3x fa-caret-up"></span></a>
                            <br/><span style="font-size: 1.7em">{{$answer->votes}}</span><br/><span class="hidden-xs">votes<br/></span><br/>

                            @if((Session::get('is_admin') === 1 || Session::get('is_admin') === 2 || Session::get('is_admin') === 3) and Session::get('id') === $answer->user_id and ($question->accepted_answer_id === 0 || $question->accepted_answer_id === 2))
                            <span class="tags">
                                    <a href="{{url("/post/edit/$answer->id")}}" class="tag"><span class="label label-info"> Edit </span></a> 
                            </span>
                            @elseif(Session::get('is_admin') === 0)
                                @if($answer->created_at != $answer->updated_at and ($question->accepted_answer_id === 0 || $question->accepted_answer_id === 2))
                                <span class="hidden-xs label label-info">Edited <span data-time-format="time-ago" data-time-value="{{strtotime($answer->updated_at)}}"></span></span>
                                <span class="visible-xs label label-warning" style="padding: .3em 0 .3em 0"><i class="fa fa-comments"></i></span>
                                @else

                                @endif
                            @endif

                            @if (Session::get('id') === $first_post->user_id && $question->accepted_answer_id === 0)
                                
                            @endif
                        </center>
                        <br/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-sm-12 poster">
                            answer given by
                            <a href="{{url("/profile/$answer->username")}}">
                                {{$answer->username}}</a> on <span data-time-format="time-ago" data-time-value="{{strtotime($answer->created_at)}}"></span>
                        </div>
                        <div class="col-sm-12 poster">
                            Sumber :
                             @foreach($answer['sumbers'] as $sumber)<a href=""><span class="">{{$sumber}} ,</span></a>&nbsp;
                            @endforeach
                        </div>
                        <div class="col-sm-12 poster">
                             @foreach($answer['files'] as $file)
                             @if($file === '')

                             @else
                             File :
                             <a href="{{URL::asset('/files/'.@$file)}}" download="{{ $file }}"><span>{{$file}}</span></a> &nbsp;
                             @endif
                            @endforeach
                        </div>
                        <div class="col-sm-12 poster">
                            @if($answer->refrence === '' || $answer->refrence === null)

                            @else
                            Reference :
                            <span>{{$answer->refrence}}</span>&nbsp;
                            @endif
                       </div>
                    </div>
                </div>
            </div>
            <hr/>
            @else
            <div class="row" id="{{$answer->id}}">
                <div class="col-xs-12">
                    <div class="col-xs-10 post">
                        {!! nl2br($answer->post_content) !!}
                    </div>
                    <div class="col-xs-2">
                        <center>
                            &nbsp;<a href="
                                    {{ (!$answer->voted) ? url("/vote/$answer->id") : url("/unvote/".$answer->id)}}"
                                    {!! ($answer->voted) ? 'data-toggle="tooltip" data-placement="top" title="You already voted for this post"' : '' !!}><span class="
                                     @if (!$answer->voted)
                                        unvoted
                                    @endif
                                    fa fa-3x fa-caret-up"></span></a>
                            <br/><span style="font-size: 1.7em">{{$answer->votes}}</span><br/><span class="hidden-xs">votes<br/></span><br/>
                        <br/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-sm-12 poster">
                            Declined by
                            <a href="{{url("/profile/$first_post->username")}}">
                                {{$first_post->username}}
                            </a>
                        </div>
                        <div class="col-sm-12 poster">
                            answer given by
                            <a href="{{url("/profile/$answer->username")}}">
                                {{$answer->username}}</a> on <span data-time-format="time-ago" data-time-value="{{strtotime($answer->created_at)}}"></span>
                        </div>
                        <div class="col-sm-12 poster">
                            Sumber :
                             @foreach($answer['sumbers'] as $sumber)<a href=""><span class="">{{$sumber}} ,</span></a>&nbsp;
                            @endforeach
                        </div>
                        <div class="col-sm-12 poster">
                            File :
                             @foreach($answer['files'] as $file)<a href=""><span class="">{{$file}} ,</span></a>&nbsp;
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
        @endforeach
        @if(($question->accepted_answer_id === 0 || $question->accepted_answer_id === 2)and Session::get('id') === $question->user_id and $answers->count()-1 !== 0)
        <span class="tags pull-left">                   
            <form action="{{url("/question/accept-answer")}}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="question_id" value="{{$question->id}}">
                <input type="hidden" name="post_id" value="{{$answer->id}}">
                <input type="hidden" name="estimated_time" value="{{$question->estimated_time}}">
                <input type="hidden" name="estimated_time_updated" value="{{$question->estimated_time_updated}}">
                <button class="btn btn-success btn-xs btn-round" style="padding: .5em"><span class="hidden-xs"> Accept</span></button>&nbsp;
            </form>
        </span>
        <span class="tags pull-left">
            {{-- <form action="{{url("/question/decline-answer")}}" method="POST"> --}}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="question_id" value="{{$question->id}}">
                <input type="hidden" name="post_id" value="{{$answer->id}}">
            <button onclick="$('#issue').modal('show');" class="btn btn-warning btn-xs btn-round" style="padding: .5em"><span class="fa fa-cross"></span><span class="hidden-xs"> Not Yet</span></button>&nbsp;
            {{-- </form> --}}
        </span>
        @elseif($question->accepted_answer_id === 4 and Session::get('id') === $question->user_id)
        <span class="tags pull-left">                   
            <form action="{{url("/question/approve/stop")}}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="question_id" value="{{$question->id}}">
                <input type="hidden" name="issues" value="{{$issues->issue}}">
                <button class="btn btn-success btn-xs" style="padding: .5em"><span class="hidden-xs"> Accept</span></button>&nbsp;
            </form>
        </span>
        <span class="tags pull-left">
            {{-- <form action="{{url("/question/cancel/stop")}}" method="POST"> --}}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="question_id" value="{{$question->id}}">
                <input type="hidden" name="issues" value="{{$issues->issue}}">
                <button onclick="$('#stopa').modal('show');" class="btn btn-danger btn-xs" style="padding: .5em"><span class="hidden-xs"> Decline</span></button>&nbsp;
            {{-- </form> --}}
        </span>
        @else

        @endif
    </div>

    <div class="pull-right">
        {{$answers->links()}}
    </div>
    <div class="clearfix"></div>

    @if (Session::has('username'))
        @if ($question->accepted_answer_id === 0 || $question->accepted_answer_id === 2)
            @if(Session::get('is_admin') === 1 || Session::get('is_admin') === 2 || Session::get('is_admin') === 3)
            <form action="{{url("/question/answer")}}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="question_id" value="{{$question->id}}">
                <div class="form-group">
                    <label class="control-label" for="first_post">Give your Response</label>
                    <textarea class="form-control" style="height:150px" name="content"></textarea>
                </div>  
                    @if (Session::has('username'))
                    {{-- <div class="form-group label-floating">
                        <label class="control-label" for="sumber">Sumber</label>
                        <select class="form-control" id="sumber_name" name="sumber_name" required="">
                            @foreach($sumber as $sumber)   
                                <option value="{{$sumber->sumber_name}}">{{$sumber->sumber_name}}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="form-group" id="sumbers-div">
                        <label class="control-label" for="sumber">Sumber</label>
                        <select class="tags-picker form-control" id="sumbers" name="sumbers[]" multiple required>
                            @foreach($sumbers as $sumber)
                                <option value="{{$sumber->id}}">{{$sumber->sumber_name}}</option>
                            @endforeach
                        </select>
                        <script>
                            $(".tags-picker").tokenize2({
                                tokensMaxItems: 5,
                                dataSource: 'select',
                                placeholder: 'Type something to start',
                                searchFromStart: false,
                                displayNoResultsMessage: true,
                                noResultsMessageText: '&nbsp; No tags found. Try typing different term.'
                            });
                        </script>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="refrence">Reference</label>
                        <textarea class="form-control" style="height:150px" name="refrence"></textarea>
                    </div> 

                    {{-- <div class="form-group" id="refrences-div">
                        <label class="control-label" for="refrence">Reference</label>
                        <select class="tags-picker form-control" id="refrence" name="refrence[]" multiple>
                        </select>
                        <script>
                            $(".tags-picker").tokenize2({
                                tokensMaxItems: 0,
                                dataSource: 'select',
                                placeholder: 'Add refrence here',
                                tokensAllowCustom: true,
                                searchFromStart: false,
                                delimiter: [',', ' ', '\t', '\n', '\r\n'],
                            });
                        </script>
                    </div> --}}
                    @endif
            {{--  --}}

        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}
        <div class="" id="increment1">
            <input type="file" name="filename[]" id="file" class="">
            <div class="input-group-btn"> 
              <button class="btn btn-success" id="success1" type="button"><i class="glyphicon glyphicon-plus"></i></button>
            </div>
          </div>
          <div class="clone hide" id="clone1">
            <div id="group1" class="" style="margin-top:10px">
              <input type="file" name="filename[]" id="file" class="">
              <div class="input-group-btn"> 
                <button id="danger1" class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i></button>
              </div>
            </div>
          </div>
        {{--  --}}
                <div class="form-group right">
                    <button type="submit" class="btn-block btn btn-primary"><span class="fa fa-comments"></span> Answer</button>
                </div>
            </form>
            @elseif(Session::get('id') === $answer->user_id)
            <span class="tags pull-left">
                Estimate Time:                     &nbsp;
                    @foreach ($category as $item)
                    <a href="" class="tag"><span class="label label-info">{{$question->estimated_time}}</span></a> 
                    @endforeach
            </span>
            
            @if($question->estimated_time !== $question->estimated_time_updated)
                <div class="col-sm-12 poster" style="padding-left : 0px">
                    Estimated Time Updated by
                     <a href="">{{$admins->username}}</a></span>
                </div>
            @else

            @endif
            {{-- <form action="{{url("/question/answer")}}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="question_id" value="{{$question->id}}">
                <div class="form-group">
                    <label class="control-label" for="first_post">Give your answer</label>
                    <textarea class="form-control" rows="7" name="post_content" required></textarea>
                </div>
                <div class="form-group right">
                    <button type="submit" class="btn-block btn btn-success"><span class="fa fa-comments"></span> Answer</button>
                </div>
            </form> --}}
         @endif
        @endif
    @else
        {{-- <h3>Think you know the answer? Join the community to start contributing!</h3>
        <button class="btn btn-block btn-primary" onclick="$('#loginModal').modal('show');"><span class="fa fa-sign-in"></span> Login/Signup</button> --}}
    @endif

    <script type="text/javascript">
        $(document).ready(function() {
    
        $("#success1").click(function(){ 
          var html = $("#clone1").html();
          $("#increment1").after(html);
        });

        $("body").on("click","#danger1",function(){ 
          $(this).parents("#group1").remove();
        });
    
        });
    
    </script>
    @stop
</body>

@section('sidebar')
    @extends('layouts.sidebar')
    @if($question->accepted_answer_id === 0 || $question->accepted_answer_id === 2)
    <button class="btn btn-block btn-primary" style="background-color: #f44336" onclick="$('#stop').modal('show');">Stop Request</button>
    @elseif($question->accepted_answer_id === 1 || $question->accepted_answer_id === 5)
    @if(Session::get('is_admin') === 0)
        @if($question->additional_information === null)
        <button class="btn btn-block btn-primary" onclick="$('#info').modal('show');">Give Additional Information</button>
         @else
            
        @endif
    @elseif(Session::get('is_admin') === 1 || Session::get('is_admin') === 2 || Session::get('is_admin') === 3)
         @if($question->additional_information_admin === null)
         <button class="btn btn-block btn-primary" onclick="$('#info').modal('show');">Give Additional Information</button>
         @else
            
        @endif
    @endif
    @endif
    <hr/>
    <div class="row">
        <div class="col-sm-12"
             data-mobile-iframe="true">
        </div>
    </div>
    <div class="row hidden-xs">
        <div class="col-sm-4" style="color:gray">asked</div>
        <div class="col-sm-8"><span data-time-value="">{{$question->created_at}}</span></div>
    </div>

    <div class="row hidden-xs">
        <div class="col-sm-4" style="color:gray">by</div>
        <div class="col-sm-8">
            <a href="{{url("/profile/$first_post->username")}}">
                {{$first_post->username}}
            </a></div>
    </div>
    <div class="row hidden-xs">
        <div class="col-sm-4" style="color:gray">active</div>
        <div class="col-sm-8"><span data-time-value="">{{$last_post->created_at}}</span></div>
    </div>
    <div class="row hidden-xs">
        <div class="col-sm-4" style="color:gray">votes</div>
        <div class="col-sm-8">{{$first_post->votes}}</div>
    </div>
    <hr/>
    @if($question->accepted_answer_id === 1 || $question->accepted_answer_id === 5)
    
    <h4>Update</h4>
        @if($question->additional_information !== null)
        Footnote User  : {{$question->additional_information}}
        @elseif($question->additional_information_admin !== null)
        Footnote Admin : {{$question->additional_information_admin}}
        @endif
    <hr/>
    @else

    @endif
    @if($question->is_give === 0)

    @else
    
    <h4>Update</h4>
    <div class="row hidden-xs">
        <div class="col-sm-5" style="color:gray">Stopped</div>
        <div class="col-sm-5">{{$question->is_give}} Time</div>
    </div>
    <div class="row hidden-xs">
        <div class="col-sm-5" style="color:gray">Reason stop</div>
        <div class="col-sm-5">{{$question->additional_information_admin}}</div>
    </div>
    <hr/>
    @endif
    @parent
@stop
</html>