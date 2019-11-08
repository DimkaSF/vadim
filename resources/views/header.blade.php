<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/index.css')}}">

    <script
            src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


    <title>Fuck yeah</title>
</head>
<body>
    <nav class="navbar navbar-expand-sm navbar-light my_container">
        <div class="d-sm-none">
            <a href="/" class="navbar-brand">
                <img src="{{asset("/img/logo.png")}}" alt="logo" width="100" />
            </a>
        </div>
        <button class="navbar-toggler order-first" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="toggle nav">
            <img src="{{asset("/img/buter.png")}}" height="21px" width="auto" />
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item @if (Request::path() == '/') active @endif  align-self-center">
                    <a href="/" class="nav-link">ФОТО</a>
                </li>
                <li class="nav-item @if (Request::path() == 'genres') active @endif align-self-center">
                    <a href="/genres" class="nav-link">ЖАНРЫ</a>
                </li>
                <li class="nav-item align-self-center header-logo">
                    <a href="/" class="nav-link navbar-brand" style="margin:0;">
                        <img src="{{asset("/img/logo.png")}}" alt="logo" width="100" />
                    </a>
                </li>
                <li class="nav-item align-self-center">
                    <a href="http://instagram.com/vadim_zaichikov" target="_blank" class="nav-link">ИНСТА</a>
                </li>
                <li class="nav-item @if (Request::path() == 'me') active @endif align-self-center">
                    <a href="/me" class="nav-link">КТО Я?</a>
                </li>
            </ul>
        </div>
    </nav>


