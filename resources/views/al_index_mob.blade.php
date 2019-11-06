@extends('index')

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
                    <p class="album_description">
                        @if ($al_info->description != "") {{$al_info->description}} @endif
                    </p>
                </div>
            </div>
            <div class="row">
                @foreach($photos_of_album as $photo)
                    <div class="col-12" style="padding-top: 10px">
                        <img class="carousel-image d-block" src="{{url('img/'.$al_info->slug.'/thumbs/'.$photo->photo->name)}}" width="100%"/>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script type="text/javascript" lang="javascript" src="/js/albumIndexMob.js"></script>
@endsection