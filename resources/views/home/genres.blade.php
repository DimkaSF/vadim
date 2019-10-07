@extends('header')

@section('content')
<div class="my_container genres_container">
    <div class="row pad-b-20 g_row">
        <div class="g_leftside col-12 col-md-4">
            <h1>Я снимаю</h1>
            <div class="space20"></div>
            @foreach($tags as $tag)
                <a href="/genres/{{$tag->text}}" class="tag" data-tag="{{$tag->text}}">#{{$tag->text}}</a>
            @endforeach
        </div>
        <div class="g_content col-12 col-md-8">
            <div class="row ">
                @if(isset($albums))
                    @foreach($albums as $al)
                        <div class="col-4 parent small" style="padding: 0">
                            <a style="color: inherit; text-decoration: none;" href="/album_{{$al->id}}">
                                <img class="img-home" src="{{url('img/'.$al->al_name.'/'.$al->ph_name)}}" width="100%"/>
                                <div class="overlay">
                                   <div class="name_of_album">{{$al->al_name}}</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <img id="g_img_holder" src="{{asset("/img/genres.png")}}" alt="Жанры"/>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="{{url('js/color-thief.js')}}"></script>
<script type="text/javascript">
    @if(isset($albums))
        $(function(){
            var colorThief = new ColorThief();
            $(".content").find(".g_content").find("div[class$=small]").each(function(){
                var res = colorThief.getColor($(this).find("img")[0]);
                $(this).find("div[class=overlay]").css("background", "rgb("+res[0]+", "+res[1]+", "+res[2]+", 0.8)");
            });
        });
    @endif
</script>

@endsection

