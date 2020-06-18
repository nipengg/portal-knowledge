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
    <h3>Submit a Request</h3>
    <form id="askForm" action="{{url('ask')}}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group label-floating">
            <label class="control-label" for="question_title">Title</label>
            <input class="form-control" type="text" name="question_title" value="" required/>
        </div>
        @foreach($topics as $topic)
        <input type="hidden" name="topic" value="{{$topic->topic}}">    
        @endforeach
        <div class="form-group label-floating">
            <label class="control-label" for="first_post">Describe your request</label>
            <textarea class="form-control" rows="7" name="first_post" required></textarea>
        </div>
            <div class="form-group label-floating">
                <label class="control-label" for="security">Summary of the request</label>
                <textarea class="form-control" rows="7" name="summary" required></textarea>
            </div>      
        <div class="form-group label-floating">
            <label class="control-label" for="category">Category</label>
            <select class="form-control" id="category_name" name="category_name" required="">
                <option value="LEFO">LEFO</option>
                <option value="Review">Review</option>
            </select>
        <div id="time">
            Estimated Time : <input type="datetime-local" name="estimated" id="estimated" required>
        </div>
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
                <label class="control-label" for="theme">Theme</label>
                <select class="form-control" id="theme" name="theme" required="">
                    @foreach($themes as $theme)   
                        <option value="{{$theme->id}}">{{$theme->theme}}</option>
                    @endforeach
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
        
        <div class="form-group label-floating">
            <label class="control-label" for="theme">Security</label>
            <select class="form-control" id="security" name="security" required="">
                    <option value="sharing">Sharing</option>
                    <option value="konfidensial">Konfidensial</option>
        </select>
        </div>
        
        <div hidden id="user">
        <div class="form-group" id="tags-div">
            Tag people: <span class="tag-error">You need to use at least one user!</span>
            <select class="tags-picker form-control" id="users" name="users[]" multiple>
                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->username}}</option>
                @endforeach
            </select>
            <script>
                $(".tags-picker").tokenize2({
                    tokensMaxItems: 5,
                    dataSource: 'select',
                    placeholder: 'Type something to start',
                    searchFromStart: false,
                    displayNoResultsMessage: true,
                    noResultsMessageText: '&nbsp; No users found. Try typing different User.'
                });
            </script>
        </div>
        </div>

        <div hidden id="department">
            <div class="form-group" id="tags-div">
                Tag Department: <span class="tag-error">You need to use at least one department!</span>
                <select class="tags-picker form-control" id="departments" name="departments[]" multiple>
                    @foreach($department as $department)
                        <option value="{{$department->department_id}}">{{$department->department_name}}</option>
                    @endforeach
                </select>
                <script>
                    $(".tags-picker").tokenize2({
                        tokensMaxItems: 5,
                        dataSource: 'select',
                        placeholder: 'Type something to start',
                        searchFromStart: false,
                        displayNoResultsMessage: true,
                        noResultsMessageText: '&nbsp; No departments found. Try typing different Department.'
                    });
                </script>
            </div>
            </div>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#security').change(function () {
                    $(this).find("option:selected").each(function(){
                        var optionValue = $(this).attr("value");
                        if(optionValue === 'konfidensial'){
                            $("#user").show();
                            $("#department").show();
                        } 
                        else if(optionValue === 'sharing'){
                            $("#user").hide();
                            $("#department").hide();
                        }
                    });
                });
            });
        </script>

        <div class="form-group label-floating">  
            <button type="submit" id="btn-custom" class="btn-block btn btn-primary">Submit Request</button>
        </div>
    </form>

    @section('sidebar')
    @extends('layouts.sidebar')
   
    @parent
@stop
@endsection
