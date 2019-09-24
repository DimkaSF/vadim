@extends('header')

@section('content')
<div class="row h-100">
    <div class="g_leftside col-12 col-md-3">
        <h2>Поиск альбомов</h2>
        <form id="searchAlbumByName" action="/search/getalbum" method="POST">
            <span class="input-group">
                <input class="form-control" placeholder="Название альбома" name="name" />
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                        <img src="/img/search.png" alt="Поиск" />
                    </button>
                </div>
            </span>
        </form>
        <div id="wordCloud">

        </div>
    </div>
    <div class="g_content col-12 col-md-9">

    </div>
</div>

<script src="{{asset('/js/jqcloud.js')}}"></script>
<link rel="stylesheet" href="{{asset('/css/jqcloud.css')}}">
<script type="text/javascript">
    $(function(){
        $.get(
            "/genres/gettags",
            function(data){
                console.log(data);
                $('#wordCloud').jQCloud(data);
            }
        );
    });
</script>

@endsection

