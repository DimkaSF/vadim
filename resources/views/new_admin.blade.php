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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" type="text/css">
    <script
            src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous"></script>

    <script src='{{asset("js/magicsuggest/magicsuggest.js")}}'></script>
    <link rel="stylesheet" href="{{asset('js/magicsuggest/magicsuggest-min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.3/cropper.min.css" >
    <link rel="stylesheet" href="{{asset('css/index.css')}}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.3/cropper.min.js"></script>
    <script src="/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script src="/js/plupload-2.3.6/js/plupload.full.min.js"></script>
    <script src="/js/plupload-2.3.6/js/jquery.ui.plupload/jquery.ui.plupload.min.js"></script>
    <script type="text/javascript" src="js/plupload-2.3.6/js/i18n/ru.js"></script>

    <title>Админка</title>
</head>
<body>
    <div class="container-fluid">

        <div class="row add_al">
            <div class="col-md-6">
                <form action="/admin/send_photo" id="formSendPic" type="POST" class="form_style dropzone">
                    <input type="text" value="lll" name="nameOfAlbum" placeholder="Название альбома" style="width:100%" required />
                    <div class="space5"></div>
                    <textarea name="albumDesc" rows="3" cols="50" style="width: 100%" placeholder="Описание альбома"></textarea>
                    <div class="helper">Максимум 255 символов</div>
                    <div class="space5"></div>
                    <input name="tags" type="text" />
                    <div class="space5"></div>
                    <div id="containerUploader">
                        <table id="preview" width="100%">
                            <thead>
                                <tr>
                                    <td>Картинка</td>
                                    <td>Имя</td>
                                    <td>Размер</td>
                                    <td></td>
                                    <td>Выбрать как обложку</td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <button id="pickFiles">Выбрать файлы</button>

                    <input type="submit" />
                </form>
            </div>
            <div class="col-md-6 pt-5">
                <div class="workWithCover"></div>

                <div>
                    <h2>Удаление</h2>
                    <input name="delAl" placeholder="Выбери альбом" />
                </div>
            </div>
        </div>
    </div>

    <script src="/js/admin.js"></script>

</body>
</html>
