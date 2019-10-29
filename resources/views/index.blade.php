@include('header')

<div class="content">
    <div id="before-load">
        <img src="{{asset("/img/logo.png")}}" alt="logo" />
    </div>

    @yield('content')

    <script type="text/javascript">
        $(function(){
            if($(window).width() < 425){
                $(".footer").find("div[class$=links]").addClass("justify-content-between");
                $(".navbar-brand").find("img:first").attr("src", "/img/logo_m.png");
            }
        });
        $(document).ready(function () {
            $('#before-load').fadeOut(1500);
        });
    </script>
</div>

<div class="row footer">
    @include('footer')
</div>