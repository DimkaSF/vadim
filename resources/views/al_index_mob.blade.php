@extends('header')

@section('content')
    <div id="slide_down">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">

                    <span class="al_name">
                      {{$al_info->name}}
                    </span>
                    <small>
                      @foreach($tags as $tag)
                        <a href="/genres/{{$tag->tag}}" class="taglink text-lowercase">#{{$tag->tag}}</a>
                      @endforeach
                    </small>
                </div>

            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <p>
                        @if ($al_info->description == "") <p class="album_description">Описание отсутствует</p>
                        @else <p class="album_description">{{$al_info->description}}</p>
                        @endif
                    </p>
                </div>
            </div>
            <div class="row">
                @foreach($photos_of_album as $photo)
                    <div class="col-12" style="padding-top: 10px">
                        <img class="carousel-image d-block" src="{{url('img/'.$al_info->name.'/'.$photo->photo->name)}}" width="100%"/>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <script type="text/javascript" lang="javascript">
        var next;
        $(function(){
            $(".footer").find("div[class$=links]").addClass("justify-content-between");
        })
        $(window).on('load', function(){
            $('#before-load').find('i').fadeOut().end().delay(400).fadeOut('slow');
            $('#slide_down').slideDown(1000);
            next = $(".carousel-control-next");

        });

        $(".carousel-image").on("click", function(){
            next.click();
        });
    </script>

@endsection