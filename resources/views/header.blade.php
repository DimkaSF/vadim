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

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
          rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
          crossorigin="anonymous">

    {{--<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>--}}
    <title>Fuck yeah</title>
</head>
<body>

    <nav class="navbar navbar-expand-sm navbar-light">
        <div class="container">
            <div class="d-sm-none">
                <a href="#" class="navbar-brand">
                    {{--<img src="https://getbootstrap.com/docs/4.1/assets/brand/bootstrap-solid.svg" alt="logo" width="30" height="30">--}}
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="toggle nav">
                <span class="navbar-toggler-icon "></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item active ">
                        <a href="/" class="nav-link">Фото</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Жанры</a>
                    </li>
                    <li class="nav-item">
                        <div class="d-none d-sm-block">
                            <a href="#" class="nav-link navbar-brand" style="margin:0;">
                                <img src="https://getbootstrap.com/docs/4.1/assets/brand/bootstrap-solid.svg" alt="logo" width="50" height="50">
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Инста</a>
                    </li>
                    <li class="nav-item">
                        <a href="/me" class="nav-link">Кто я?</a>
                    </li>
                </ul>
            </div>
        </div>


    </nav>



<script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <div id="before-load">
        <!-- Иконка Font Awesome -->
        <i class="fa fa-spinner fa-spin"></i>
    </div>


<div class="content">
    @yield('content')
</div>
    <script type="text/javascript">
        $(window).on('load', function () {
             $('#before-load').find('i').fadeOut().end().delay(400).fadeOut('slow');
        });

    </script>

</body>
</html>