@extends('index')

@section('content')
    <div class="row" style=" margin-right: 0px; margin-left: 0px">
        @foreach($name_with_title as $album)
            <div class="setcss">
                <a style="color: inherit; text-decoration: none;" href="/album/{{$album->slug}}">
                    <img class="img-home" src="{{url('img/'.$album->slug.'/'.$album->get_title->name)}}">
                    <div class="overlay">
                       <div class="name_of_album">{{$album->name}}</div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    <script src="{{url('js/color-thief.js')}}"></script>
    <script type="text/javascript" src="{{url("/js/homepage.js")}}"></script>
@endsection