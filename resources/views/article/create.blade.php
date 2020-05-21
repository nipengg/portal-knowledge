@extends('layouts.masterr')
  
@section('content')
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
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h3>Add New Article</h3>
        </div>
    </div>
</div>
   
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
   
<form action="{{ route('article.store') }}" method="POST" enctype="multipart/form-data">
    {!! csrf_field() !!}
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group label-floating">
                <label class="control-label" for="security">Title</label>
                <input type="text" name="title" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group label-floating">
                <label class="control-label" for="security">Content</label>
                <textarea class="form-control" style="height:150px" id="content" name="content"></textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group label-floating">
                <label class="control-label" for="security">Summary of the article</label>
                <input type="text" name="summary" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group label-floating">
            <label class="control-label" for="security">Security</label>
            <select class="form-control" id="security" name="security" required="">  
                    <option value="sharing">Sharing</option>
                    <option value="konfidensial">Konfidensial</option>
            </select>
            </div>
        </div>

        <div hidden class="col-xs-12 col-sm-12 col-md-12" id="user">
            <div class="form-group" id="users-div" style="margin : 0px">
                <label class="control-label" for="user">Tag User</label>
                <select class="tags-picker form-control" id="users" name="users[]" multiple>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->username}}</option>
                    @endforeach
                </select>
                <script>
                    $(".tags-picker").tokenize2({
                        tokensMaxItems: 5,
                        dataSource: 'select',
                        placeholder: 'Qtc., other user',
                        searchFromStart: false,
                        displayNoResultsMessage: true,
                        noResultsMessageText: '&nbsp; No users found. Try typing different user.'
                    });
                </script>
            </div>
        </div>

        <div hidden class="col-xs-12 col-sm-12 col-md-12" id="department">
            <div class="form-group" id="users-div" style="margin : 0px">
                <label class="control-label" for="user">Tag Department</label>
                <select class="tags-picker form-control" id="departments" name="departments[]" multiple>
                    @foreach($department as $department)
                        <option value="{{$department->department_id}}">{{$department->department_name}}</option>
                    @endforeach
                </select>
                <script>
                    $(".tags-picker").tokenize2({
                        tokensMaxItems: 10,
                        dataSource: 'select',
                        placeholder: 'Qtc., Departments',
                        searchFromStart: false,
                        displayNoResultsMessage: true,
                        noResultsMessageText: '&nbsp; No Departments found. Try typing different department.'
                    });
                </script>
            </div>
        </div>
       
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group label-floating">
                <label class="control-label" for="theme">Theme</label>
                <select class="form-control" id="theme" name="theme" required="">
                    @foreach($themes as $theme)   
                        <option value="{{$theme->id}}">{{$theme->theme}}</option>
                    @endforeach
            </select>
            </div>
        </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group" id="sumbers-div" style="margin : 0px">
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
                    placeholder: 'Qtc., Free Topic , Bedah Buku , Specific project',
                    searchFromStart: false,
                    displayNoResultsMessage: true,
                    noResultsMessageText: '&nbsp; No tags found. Try typing different term.'
                });
            </script>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group" id="tags-div">
        <label class="control-label" for="tag">Tags</label>
        <select class="tags-picker form-control" id="tags" name="tags[]" multiple required>
            @foreach($tags as $tag)
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
                noResultsMessageText: '&nbsp; No tags found. Try typing different term or add some new tags.'
            });
        </script>
    </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group" id="refrences-div">
            <label class="control-label" for="refrence">Refrence</label>
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
        </div>
    </div>
        
     <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
     <div class="col-xs-12 col-sm-12 col-md-12">
     <label class="control-label" for="file">File Refrences</label>
     <div class="input-group control-group increment" id="increment1">
         <input type="file" name="file[]" id="file" class="form-control">
         <div class="input-group-btn"> 
           <button id="success1" class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i></button>
         </div>
       </div>
       <div class="clone hide" id="clone1">
         <div id="group1" class="control-group input-group" style="margin-top:10px">
           <input type="file" name="file[]" id="file" class="form-control">
           <div class="input-group-btn"> 
             <button id="danger1" class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i></button>
           </div>
         </div>
       </div>
    </div>
    

     {{-- <div class="col-xs-12 col-sm-12 col-md-12">
     <label class="control-label" for="refrence">Refrences</label>
     <div class="input-group control-group increment" id="increment2">
         <input type="text" name="refrence[]" id="refrence" class="form-control">
         <div class="input-group-btn"> 
           <button id="success2" class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i></button>
         </div>
       </div>
       <div class="clone hide" id="clone2">
         <div id="group2" class="control-group input-group" style="margin-top:10px">
           <input type="text" name="refrence[]" id="refrence" class="form-control">
           <div class="input-group-btn"> 
             <button id="danger2" class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i></button>
           </div>
         </div>
       </div>
    </div> --}}

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn-block btn btn-primary">Submit</button>
        </div>
    </div>
</form>

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

      $("#success1").click(function(){ 
          var html = $("#clone1").html();
          $("#increment1").after(html);
      });

      $("body").on("click","#danger1",function(){ 
          $(this).parents("#group1").remove();
      });

    //   $("#success2").click(function(){ 
    //       var html = $("#clone2").html();
    //       $("#increment2").after(html);
    //   });

    //   $("body").on("click","#danger2",function(){ 
    //       $(this).parents("#group2").remove();
    //   });
    });
</script>

@endsection