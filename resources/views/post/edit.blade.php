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

@if(Session::get('id') !== $posts->user_id)
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h3 style="color: white">DANGER</h3>
        </div>
    </div>
</div>
<br/>
<div class="jumbotron">
    <center>
        <h6>You don't have access in this area</h6>
    </center>
</div>
@else
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h3>Edit post</h3>
        </div>
    </div>
</div>

<form action="{{url("/post/edit/id/".$posts->id)}}" method="POST">
    {!! csrf_field() !!}
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group label-floating">
                <label class="control-label" for="security">Content</label>
                <input type="hidden" name="id" value="{{ $posts->id }}">
                <textarea id="content" class="form-control" style="height:150px" name="content">{{$posts->post_content}}</textarea>
            </div>
        </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group" id="sumbers-div">
            <label class="control-label" for="sumber">Sumber</label>
            <select class="tags-picker form-control" id="sumbers" name="sumbers[]" multiple required>
                @foreach($sumber as $sumber)
                    <option value="{{$sumber->id}}" required>{{$sumber->sumber_name}}</option>
                 @endforeach
            </select>
            <small class="form-text text-primary">Sumber wajib diisi kembali</small>
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
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn-block btn btn-primary">Submit</button>
        </div>
    </div>
   
</form>
@endif
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@stop