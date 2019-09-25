<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/index.css')}}">

    <script
            src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous"></script>

    <title>Fuck yeah</title>


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
          rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
          crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="{{url('js/color-thief.js')}}"></script>
    <style>
        /* Make the image fully responsive */
        .carousel-inner img {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>

    <div id="before-load">
        <img src="{{asset("/img/logo.png")}}" alt="logo" />
    </div>


    <div id="slide_down">
        <div class="row">
            <div class="col-md-6 col-10 name_of_album_in_index">
                <p style="text-transform: uppercase; font-size: 30px">
                    {{$al_info->name}}
                    <span style="font-size:14px; text-transform: none">
                        @foreach($tags as $tag)
                            #{{$tag->tag}}
                        @endforeach
                    </span>
                </p>
            </div>

            <div class="col-md-6 col-2 justify-content-end close_album">
                <a style="color: inherit; text-decoration: none;" href="/">
                    <i class="fa fa-times fa-2x"></i>
                </a>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12 col-sx-12 album_description_container">
                @if ($al_info->description == "") <p class="album_description">Описание отсутствует</p>
                @else <p class="album_description">{{$al_info->description}}</p>
                @endif
            </div>
        </div>
        <div class="row">
            <div id="demo" class="carousel slide" data-ride="carousel" style=" width: 100%">
                <div class="carousel-inner">
                    @foreach($photos_of_album as $photo)
                        @if($loop->index == 0)
                            <div class="carousel-item active carousel-div">
                                <img id="title" class="carousel-image d-block" src="{{url('img/'.$al_info->name.'/'.$photo->photo->name)}}" >
                            </div>
                        @else
                            <div class="carousel-item carousel-div"  >
                                <img class="carousel-image d-block" src="{{url('img/'.$al_info->name.'/'.$photo->photo->name)}}">
                            </div>

                        @endif
                    @endforeach
                </div>

                <!-- Left and right controls -->
                <a class="carousel-control-prev" href="#demo" data-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </a>
                <a class="carousel-control-next" href="#demo" data-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </a>

            </div>
            <ul class="list-inline">
                @foreach($photos_of_album as $photo)
                    <li class="list-inline-item" style="padding-top: 5px" data-target="#demo" data-slide-to="{{$loop->index}}">
                        <img style="width: 40px;" src="{{url('img/'.$al_info->name.'/'.$photo->photo->name)}}">
                    </li>
                @endforeach
            </ul>

        </div>

        <div class="row justify-content-end" style="padding-right: 3%">
                @if($al_info->id != 1)
                    <a style="color: inherit; text-decoration: none;" href="/album_{{$prev_id}}">
                        <i class="fa fa-fast-backward fa-2x" style="margin-right: 5px"></i>
                    </a>
                @endif

                @if($is_last != true)
                    <a style="color: inherit; text-decoration: none;" href="/album_{{$next_id}}">
                        <i class="fa fa-fast-forward fa-2x"></i>
                    </a>
                @endif
        </div>
    </div>


    <script type="text/javascript">
        $(window).on('load', function(){
            $('#before-load').find('i').fadeOut().end().delay(400).fadeOut('slow');


            var colorThief = new ColorThief();
            var image = document.getElementById('title');
            //var container = document.getElementsByClassName('container');
            var slide_down = document.getElementById('slide_down');
            var name_of_album_in_index = document.getElementsByClassName('name_of_album_in_index');
            var close_album = document.getElementsByClassName('close_album');
            var album_desc = document.getElementsByClassName('album_description');
            var arrows = document.getElementsByClassName('fa');

            var res = colorThief.getColor(image);

            slide_down.style.backgroundColor = "rgb("+res[0]+", "+res[1]+", "+res[2]+")";
            name_of_album_in_index[0].style.color = "rgb("+(255-res[0])+", "+(255-res[1])+", "+(255-res[2])+")";
            close_album[0].style.color = "rgb("+(255-res[0])+", "+(255-res[1])+", "+(255-res[2])+")";
            album_desc[0].style.color = "rgb("+(255-res[0])+", "+(255-res[1])+", "+(255-res[2])+")";
            //container[0].style.backgroundColor = "rgb("+res[0]+", "+res[1]+", "+res[2]+")";

            for(var i =1; i<arrows.length; i++){
                console.log( arrows[i]);
                arrows[i].style.color = "rgb("+(255-res[0])+", "+(255-res[1])+", "+(255-res[2])+")";
            }

            $('#slide_down').slideDown(1000);
        });

    </script>

</body>
</html>