@extends('layouts.masterr')

@section('title', 'Ask a question');

@section('content')
    <style>
        .tags-picker{
            border: 0px solid black;
        }
        .tag-error{
            display: none;
            color: red;
        }
        /*tokenizer item*/
        .token{
            /*color: cornflowerblue;*/
            /*background-color: lightblue !important;*/
            /*font-family: "Roboto", "Helvetica", "Arial", sans-serif;*/
            /*font-size: .7em;*/
            border: 0px solid black !important;
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
    <h3>Q&A</h3>
    <form id="qaForm" action="{{url('/qa/create')}}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group label-floating">
            <label class="control-label" for="qa_title">Q&A title</label>
            <input class="form-control" type="text" name="qa_title" value="" required/>
        </div>    
        <div class="form-group label-floating">
            <label class="control-label" for="admin">Admin</label>
            <select class="form-control" id="admin" name="admin" required="">
                @foreach($admins as $admin)   
                <option value="{{$admin->id}}">{{$admin->username}}</option>
            @endforeach
            </select>
        </div>

            <div class="form-group label-floating">
            <label class="control-label" for="security">Security</label>
            <select class="form-control" id="security" name="security" required="">  
                    <option value="sharing">Sharing</option>
                    <option value="konfidensial">Konfidensial</option>
            </select>
            </div>
            
        <div class="form-group" id="tags-div">
            Tags: <span class="tag-error">You need to use at least one tag!</span>
            <select class="tags-picker form-control" id="tags" name="tags[]" multiple required>
                   @foreach($allowed_tags as $tag)
                        <option value="{{$tag->id}}">#{{$tag->tag}}</option>
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
        <br>
        {{--  --}}
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <div class="input-group control-group increment" >
            <input type="file" name="filename[]" class="form-control">
            <div class="input-group-btn"> 
              <button class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i></button>
            </div>
          </div>
          <div class="clone hide">
            <div class="control-group input-group" style="margin-top:10px">
              <input type="file" name="filename[]" class="form-control">
              <div class="input-group-btn"> 
                <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i></button>
              </div>
            </div>
          </div>
        {{--  --}}
            <button type="submit" id="btn-custom" class="btn-block btn btn-primary">Ask!</button>
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function() {
    
          $(".btn-success").click(function(){ 
              var html = $(".clone").html();
              $(".increment").after(html);
          });
    
          $("body").on("click",".btn-danger",function(){ 
              $(this).parents(".control-group").remove();
          });
    
        });
    
    </script>
@stop
