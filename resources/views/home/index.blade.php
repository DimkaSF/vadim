@extends('header')

@section('content')
    <script type="text/javascript">
        $(function(){
            var items = [2, 3, 5, 6];
            var grid = items[Math.floor(Math.random()*items.length)];
            $("<style></style>")
            .prop("type", "text/css")
            .html("\
            .all_father div:nth-child("+grid+"n) {\
                grid-column:span 2;\
                grid-row: span 2;\
            }")
            .appendTo("head");
            });

    </script>

    <div class="row all_father" style=" margin-right: 0px; margin-left: 0px">
        @foreach($name_with_title as $album)
            <div class="small parent">
                <a style="color: inherit; text-decoration: none;" href="/album_{{$album->id}}">
                <img class="img-home" src="{{url('img/'.$album->name.'/'.$album->get_title->name)}}">
                <div class="overlay">
                   <div class="name_of_album">{{$album->name}}</div>
                </div>
                </a>
            </div>
        @endforeach
    </div>

    <script src="{{url('js/color-thief.js')}}"></script>

    <script type="text/javascript">
        $(window).on('load', function () {
            var colorThief = new ColorThief();

            $(".content").find("div[class^=small]").each(function(){
                var res = colorThief.getColor($(this).find("img")[0]);
                $(this).find("div[class=overlay]").css("background", "rgb("+res[0]+", "+res[1]+", "+res[2]+", 0.8)");
            });
        });
    </script>
@endsection