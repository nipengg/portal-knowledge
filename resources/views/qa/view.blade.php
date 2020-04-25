@extends('layouts.qa')

@section('title', 'Laravel-based Q&A Forum');

@section('content')


<!DOCTYPE html>
<html>
	<head>
		<title>Chat</title>
    <style>
      @import url(https://fonts.googleapis.com/css?family=Lato:400,700);
*, *:before, *:after {
  box-sizing: border-box;
}

li{
	list-style-type: none;
}

.chat {
  width: 850px;
  float: left;
  background: #F2F5F8;
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
  color: #434651;
}
.chat .chat-header {
  padding: 20px;
  border-bottom: 2px solid white;
}
.chat .chat-header img {
  float: left;
}
.chat .chat-header .chat-about {
  float: left;
  padding-left: 10px;
  margin-top: 6px;
}
.chat .chat-header .chat-with {
  font-weight: bold;
  font-size: 16px;
}
.chat .chat-header .chat-num-messages {
  color: #92959E;
}
.chat .chat-header .fa-star {
  float: right;
  color: #D8DADF;
  font-size: 20px;
  margin-top: 12px;
}
.chat .chat-history {
  padding: 30px 30px 20px;
  border-bottom: 2px solid white;
  overflow-y: scroll;
  height: 575px;
}
.chat .chat-history .message-data {
  margin-bottom: 15px;
}
.chat .chat-history .message-data-time {
  color: #a8aab1;
  padding-left: 6px;
}
.chat .chat-history .message {
  color: white;
  padding: 18px 20px;
  line-height: 26px;
  font-size: 16px;
  border-radius: 7px;
  margin-bottom: 30px;
  width: 90%;
  position: relative;
}
.chat .chat-history .message:after {
  bottom: 100%;
  left: 7%;
  border: solid transparent;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
  border-bottom-color: #86BB71;
  border-width: 10px;
  margin-left: -10px;
}
.chat .chat-history .my-message {
  background: #86BB71;
}
.chat .chat-history .other-message {
  background: #94C2ED;
}
.chat .chat-history .other-message:after {
  border-bottom-color: #94C2ED;
  left: 93%;
}
.chat .chat-message {
  padding: 30px;
}
.chat .chat-message textarea {
  width: 100%;
  border: none;
  padding: 10px 20px;
  font: 14px/22px "Lato", Arial, sans-serif;
  margin-bottom: 10px;
  border-radius: 5px;
  resize: none;
}
.chat .chat-message .fa-file-o, .chat .chat-message .fa-file-image-o {
  font-size: 16px;
  color: gray;
  cursor: pointer;
}
.chat .chat-message button {
  float: right;
  color: #94C2ED;
  font-size: 16px;
  text-transform: uppercase;
  border: none;
  cursor: pointer;
  font-weight: bold;
  background: #F2F5F8;
}

.accept{
  padding: 30px;
  float: center;
  color: #94C2ED;
  font-size: 16px;
  text-transform: uppercase;
  border: none;
  cursor: pointer;
  font-weight: bold;
  background: #F2F5F8;
}

.accept:hover{
	color: #75b1e8;
}

.chat .chat-message button:hover {
  color: #75b1e8;
}

.online, .offline, .me {
  margin-right: 3px;
  font-size: 10px;
}

.online {
  color: #86BB71;
}

.offline {
  color: #E38968;
}

.me {
  color: #94C2ED;
}

.align-left {
  text-align: left;
}

.align-right {
  text-align: right;
}

.float-right {
  float: right;
}

.clearfix:after {
  visibility: hidden;
  display: block;
  font-size: 0;
  content: " ";
  clear: both;
  height: 0;
}

    </style>
	</head>
	<body>
		<form action="{{url("/qa/answer")}}" method="POST">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="qa_id" value="{{$qa->id}}">
				<div class="chat">
					<div class="chat-history">
						<div id="here">
						@foreach ($messages as $item)
						@if(Session::get('id') === $qa->user_id)
							@if(Session::get('id') === $item->user_id)
			
								<li class="clearfix">
								  <div class="message-data align-right">
									<span class="message-data-time" >{{$item->created_at}}</span> &nbsp; &nbsp;
									<span class="message-data-name" >{{$item->username}}</span> <i class="fa fa-circle me"></i>
									
								  </div>
								  <div class="message other-message float-right" style="position: relative">
									{{$item->post_content}}
								  </div>
								</li>
								
							@else
							<li>
								<div class="message-data">
								  <span class="message-data-name"><i class="fa fa-circle online"></i>{{$item->username}}</span>
								  <span class="message-data-time">{{$item->created_at}}</span>
								</div>
								<div class="message my-message" style="position: relative">
									{{$item->post_content}}
								</div>
							  </li>
							@endif
					  
						@elseif(Session::get('id') !== $qa->user_id)
						   @if(Session::get('id') === $item->user_id)
							<li class="clearfix">
							  <div class="message-data align-right">
								<span class="message-data-time" >{{$item->created_at}}</span> &nbsp; &nbsp;
								<span class="message-data-name" >{{$item->username}}</span> <i class="fa fa-circle me"></i>
								
							  </div>
							  <div class="message other-message float-right" style="position: relative">
								{{$item->post_content}}
							  </div>
							</li>
						   @else
							   <li>
								<div class="message-data">
								  <span class="message-data-name"><i class="fa fa-circle online"></i>{{$item->username}}</span>
								  <span class="message-data-time">{{$item->created_at}}</span>
								</div>
								<div class="message my-message" style="position: relative">
									{{$item->post_content}}
								</div>
							  </li>
						   @endif
					   @endif
					   @endforeach
					</div> <!-- end chat-history -->
				</div>
				@if($qa->accepted_qa_id === 0)
				<div class="chat-message clearfix">
					<textarea  name="post_content" id="message-to-send" placeholder ="Type your message" rows="3" required></textarea>	
					<i class="fa fa-file-o"></i> &nbsp;&nbsp;&nbsp;
					<i class="fa fa-file-image-o"></i>
					<button type="submit" id="btn-custom">Send</button>&nbsp;
				</div> <!-- end chat-message -->
				@else

				@endif
			</div>&nbsp; <!-- end chat -->
		</form>
		@if($qa->accepted_qa_id === 0 and Session::get('is_admin') == 0)
		<form action="{{url("/qa/accept-answer")}}" method="POST">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="qa_id" value="{{$qa->id}}">
			<button type="submit" class="btn btn-primary btn-block">End Chat</button>&nbsp;
		</form>
		@else
		
    @endif

	<script> 
		$(document).ready(function(){
		setInterval(function(){
			  $("#here").load(window.location.href + " #here" );
		}, 1000);
		});
		</script>
  </body>
  
  @section('sidebar')
  @extends('layouts.sidebar')
  <hr/>
  <div class="row">
      <div class="col-sm-12"
           data-mobile-iframe="true">
      </div>
  </div>
  Files :
  <div class="row hidden-xs">
      <br/>
      <span class="pull-left col-sm-4">
        @foreach($qa_files as $file)
        <a href="{{URL::asset('/files/'.@$file)}}" download="{{ $file }}" class="tag"><span class="label label-info">{{$file}}</span></a> <br/> <br/>
        @endforeach
    </span>
  </div>
  <hr/>
  @parent
@stop


</html>
@stop

