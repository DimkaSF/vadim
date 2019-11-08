<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/index.css')}}">

    <script
            src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous"></script>

    <title>{{$al_info->name}}</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
          rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
          crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="{{asset('js/moxie.js')}}"></script>
</head>
<body>
    <div id="before-load">
        <img src="{{asset("/img/logo.png")}}" alt="logo" />
    </div>

    <div id="slide_down">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <a href="/" class="close"><i class="fa fa-times fRight p-2"></i></a>
                    <span class="al_name">
                        {{$al_info->name}}
                    </span>
                    <small>
                    @foreach($tags as $tag)
                        <a href="/genres/{{$tag->tag}}" class="taglink">#{{$tag->tag}}</a>
                    @endforeach
                    </small>
                </div>
            </div>
            <div class="space20"></div>
            <div class="row justify-content-center">
                <div id="photo_car" class="carousel slide" data-ride="carousel" style="width:90%;" data-piccount="{{count($photos_of_album)}}">
                    <div class="carousel-inner">
                        @foreach($photos_of_album as $photo)
                            <div class="carousel-item carousel-div @if($loop->index == 0) active @endif">
                                <img class="carousel-image d-block" src="{{url('img/'.$al_info->slug.'/'.$photo->photo->name)}}" width="100%" height="100%"/>
                            </div>
                        @endforeach
                    </div>

                    <a class="carousel-control-next" href="#photo_car" data-slide="next" style="display: none;">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                </div>
            </div>
            <div class="space30"></div>
            <div class="space10"></div>
            <div class="containerBlock" id="gallery">
                <div class="row align-items-center justify-content-around">
                    <span id="backward"><i class="fa fa-angle-left fa-3x"></i></span>
                    <ul id="xMiniPic" class="list-inline text-center m-0 minipicul"></ul>
                    <span id="forward"><i class="fa fa-angle-right fa-3x"></i></span>
                </div>
            </div>
            <div class="space30"></div>
            <div class="row">
                <div class="col-12 text-center album_description" >
                    <small>
                        @if($al_info->description != "")
                            {{$al_info->description}}
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" lang="javascript" src="/js/albumIndexDesk.js"></script>
</body>
</html>
