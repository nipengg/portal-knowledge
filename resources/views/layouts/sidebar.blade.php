@section('sidebar')
        <div class="form-group label-floating">
            @if (Session::has('username') === false)
                <h3>Register to ask a request</h3>
                <button class="btn btn-block btn-primary" onclick="$('#loginModal').modal('show');"><span class="fa fa-sign-in"></span> Login/Signup</button>
            @endif
        </div>

        

        @if(Session::get('is_admin') === 0)
            @if(Session::get('is_approved') === 'active')

            <h5>Most popular tags:</h5>
            <span class="tags">
                @foreach (Session::get('popular_tags') as $tag)
                    <a href="{{url("/tag/$tag->tag")}}" class="tag"><span class="label label-info">#{{$tag->tag}}</span></a>
                    &nbsp;
                @endforeach
                &nbsp;
            </span>


        <form action="{{url('/ask')}}" method="GET" class="">
            {{-- <input required type="text" name="question" class="form-control" placeholder="Ex: How do I..., Have anyone ever..., etc."/> --}}
            <button class="btn btn-block btn-primary"><i class="fa fa-comment"></i> Send Request</button>
        </form>
        <form action="{{url('/add-tags')}}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            Add new tags:
            <select class="tags-picker form-control" id="tags" name="tags[]" multiple required>
            </select>
            <script>
                $(".tags-picker").tokenize2({
                    tokensMaxItems: 0,
                    dataSource: 'select',
                    placeholder: 'Add new tags here',
                    tokensAllowCustom: true,
                    searchFromStart: false,
                    delimiter: [',', ' ', '\t', '\n', '\r\n'],
                });
            </script>
            <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-plus"></i> Add tags</button>
        </form>
            @else
            
         @endif
        @endif

        @if(Session::get('is_admin') === 1 || Session::get('is_admin') === 2 || Session::get('is_admin') === 3)
            @if(Session::get('is_approved') === 'active')
        <h5>Most popular tags:</h5>
            <span class="tags">
                @foreach (Session::get('popular_tags') as $tag)
                    <a href="{{url("/tag/$tag->tag")}}" class="tag"><span class="label label-info">#{{$tag->tag}}</span></a>
                    &nbsp;
                @endforeach
                &nbsp;
            </span>
        <hr/>
        <form action="{{url('/article')}}" method="GET" class="">
            <button class="btn btn-block btn-primary"><i class="fa fa-comment"><span style="opacity:0;">T</span></i>Make new Article</button>
        </form>
        <form action="{{url('/add-tags')}}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            Add new tags:
            <select class="tags-picker form-control" id="tags" name="tags[]" multiple required>
            </select>
            <script>
                $(".tags-picker").tokenize2({
                    tokensMaxItems: 0,
                    dataSource: 'select',
                    placeholder: 'Add new tags here',
                    tokensAllowCustom: true,
                    searchFromStart: false,
                    delimiter: [',', ' ', '\t', '\n', '\r\n'],
                });
            </script>
            <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-plus"></i> Add tags</button>
        </form>
        </div>
            @elseif(Session::get('is_approved') === 'sending' || Session::get('is_approved') === 'inactive' || Session::get('is_approved') === 'reject')

            @endif
        @endif
@stop
