@extends('header')

@section('content')
<div class="my_container genres_container">
    <div class="row pad-b-20 g_row">
        <div class="g_leftside col-12 col-md-4">
            <h1>Я снимаю</h1>
            <div class="space20"></div>
            @foreach($tags as $tag)
                <p class="tag" data-tag="{{$tag->text}}">#{{$tag->text}}</p>
            @endforeach
        </div>
        <div class="g_content col-12 col-md-8">
            <img id="g_img_holder" src="{{asset("/img/genres.png")}}" alt="Жанры"/>
            <div class="row g_albums_ajax">

            </div>
        </div>
    </div>
</div>

<script src="{{url('js/color-thief.js')}}"></script>
<script type="text/javascript">
    $(".tag").on("click", function(){
        $(".tag").each(function(){
            $(this).removeClass("active");
        });
        $(this).addClass("active");

        if($("#g_img_holder")){
            $("#g_img_holder").fadeOut().end().fadeOut(500);
            $("#g_img_holder").remove();
        }
        var parent = $(".g_albums_ajax");
        var colorThief = new ColorThief();
        parent.css("display", "none");
        $.ajax({
            type: 'POST',
            url: "/genres/getalbums",
            headers:{
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr("content")
            },
            data: {
                "tag": $(this).data("tag")
            },
            success: function (data) {
                parent.html("");
                $.each(data, function(index, album){
                    $("<div class=\"col-md-4 col-12 nopadding\"></div>")
                        .append(
                            $("<div class=\"small parent\"></div>")
                                .append(
                                    $("<a href=\"/album_"+album.id+"\"></a>")
                                        .append("<img src=\"/img/"+album.al_name+"/"+album.ph_name+"\" width=\"100%\" />")
                                        .append(
                                            $("<div class=\"overlay\"></div>")
                                                .append("<div class=\"name_of_album\">"+album.al_name+"</div>")
                                        )
                                )
                        )
                    .appendTo(".g_albums_ajax");
                });
                parent.fadeIn(800);

                setTimeout(function(){
                    $(".g_albums_ajax").find("div[class^=small]").each(function(){
                        var res = colorThief.getColor($(this).find("img")[0]);
                        $(this).find("div[class=overlay]").css("background", "rgb("+res[0]+", "+res[1]+", "+res[2]+", 0.8)");
                    });
                }, 800);


            }
        });
    });
</script>

@endsection

