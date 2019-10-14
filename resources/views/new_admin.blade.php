<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />
    <link rel="stylesheet" href="/js/plupload-2.3.6/js/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('css/index.css')}}">
    <script
            src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous"></script>

    <script src='{{asset("js/magicsuggest/magicsuggest.js")}}'></script>
    <link rel="stylesheet" href="{{asset('js/magicsuggest/magicsuggest-min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.3/cropper.min.css" >
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.3/cropper.min.js"></script>
    <script src="/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script src="/js/plupload-2.3.6/js/plupload.full.min.js"></script>
    <script src="/js/plupload-2.3.6/js/jquery.ui.plupload/jquery.ui.plupload.min.js"></script>

    <title>Админка</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row mt-3" id="buttonsContainer">
            <div class="col-md-4">
                <button data-action="add_al" style="width: 100%">Добавить альбом</button>
            </div>
        </div>

        <div class="row add_al">
            <div class="col-md-6">
                <form action="/admin/send_photo" id="form_send_pic" type="POST" class="form_style dropzone">
                    <input type="text" id="name_of_album" placeholder="Название альбома" required />
                    <span class="space20"></span>
                    <input type="file" id="pic" name="picture" accept="image/*" multiple required />
                    <div class="space5"></div>
                    <textarea name="album_desc" rows="3" cols="50" style="width: 100%" placeholder="Описание альбома"></textarea>
                    <div class="helper">Максимум 255 символов</div>
                    <div class="space5"></div>
                    <input name="tags" type="text" />
                    <div class="space5"></div>
                    <div id="uploader">
                        <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <div class="work_with_cover" style="width: 100%; max-height: 800px">

                </div>
            </div>
        </div>
    </div>

    <script src="/js/admin.js"></script>

</body>
</html>
