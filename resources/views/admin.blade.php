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
    <link rel="stylesheet" href="{{asset('css/index.css')}}">
    <script
            src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous"></script>

    <script src='{{asset("js/magicsuggest/magicsuggest.js")}}'></script>
    <link rel="stylesheet" href="{{asset('js/magicsuggest/magicsuggest-min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.3/cropper.min.css" >
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.3/cropper.min.js"></script>

    <title>Fuck yeah</title>
</head>
<body>
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-md-4">
            <button data-action="add_al" style="width: 100%">Добавить альбом</button>
        </div>
        <div class="col-md-4">
            <button data-action="edit_al" style="width: 100%">Редактировать альбом</button>
        </div>
        <div class="col-md-4">
            <button data-action="delete_al" style="width: 100%">Удалить альбом</button>
        </div>
    </div>
    <div class="row add_al">
        <div class="col-md-6" id="drop-area" >
            <label>Нажми на кнопку или перетащи файлы сюда.</label>
            <form action="/admin/send_photo" id="form_send_pic" type="POST" class="form_style">
                <input type="text" id="name_of_album" placeholder="Название альбома" required />
                <span class="space20"></span>
                <input type="file" id="pic" name="picture" accept="image/*" multiple required />
                <div class="space5"></div>
                <textarea name="album_desc" rows="3" cols="50" style="width: 100%" placeholder="Описание альбома"></textarea>
                <div class="helper">Максимум 255 символов</div>
                <div class="space5"></div>
                <input name="tags" type="text" />
                <div class="space5"></div>

                <div id="image_to_upload" class="row"></div>


                <input type="submit">
            </form>
        </div>
        <div class="col-md-6">
            <div class="work_with_cover" style="width: 100%; max-height: 800px">

            </div>
        </div>
    </div>

    <div class="row edit_al">
        <div class="col-md-6" style="margin-top: 20px">
            <select name="edit_select" class="edit_select">
                <option value="0">Какой альбом правим?</option>
                @foreach($albums_names as $name)
                    <option value="{{$name->id}}" data-al_name="{{$name->name}}">{{$name->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6" style="margin-top: 20px">
            <form id="edit_new_tags" style="padding:0; width: 100%; min-height: 36px;">
                <input name="tags_edit" type="text" width="100%" />
                <span class="space5"></span>
                <button type="submit" class="col-md-3 col-12 edit_submit">Обновить теги</button>
            </form>
        </div>

        <div id="edit_al_photos" class="col-md-6">
        </div>
        <div id="edit_al_cover" class="col-md-6">
        </div>
    </div>
    <div class="row delete_al">
        <div class="col-md-12" style="margin-top: 20px">
            <select name="delete_select">
                <option>Какой альбом удаляем?</option>
                @foreach($albums_names as $name)
                    <option value="{{$name->id}}">{{$name->name}}</option>
                @endforeach
            </select>
            <button id="delete_al_button">Удалить</button>
        </div>

        <div class="col-md-12" id="delete_al_photos">

        </div>
    </div>
</div>



<script type="text/javascript">
    $(function($){

        $(".add_al").hide();
        $(".edit_al").hide();
        $(".delete_al").hide();
        $("#delete_al_button").hide();

        var tags = [];
        $.ajax({
            type: 'POST',
            url: "/admin/gettags",
            headers:{
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr("content")
            },
            processData: false,
            contentType: false,
            success: function (data) {
                $.each(data, function(index, tag){
                    tags.push(tag.tag);
                });
            }
        });
        ms = $("#form_send_pic input[name=tags]").magicSuggest({
            placeholder: 'Выбери тег',
            maxDropHeight: 145,
            noSuggestionText: 'Такого тега ещё не было.'
        });
        ms.setData(tags);

        ms_edit = $("input[name=tags_edit]").magicSuggest({
            placeholder: 'Выбери тег',
            maxDropHeight: 145,
            noSuggestionText: 'Такого тега ещё не было.',
            cls: 'col-md-8 col-12 edit_input'
        });
        ms_edit.disable();
        $("#edit_new_tags button").attr("disabled", true);
    });

    var cropper;
    var ms;
    var ms_edit;

    $("textarea[name=album_desc]").on("keyup", function(){
        if($("textarea[name=album_desc]").val().length <= 255){
            $(".helper").text("Осталось символов:" + (255-$("textarea[name=album_desc]").val().length));
        }
        else{
            $(".helper").text("Вадик, слишком много символов. Крактость - сестра  таланта :)");
        }
    });

    $("select[name=edit_select]").on("change", function(){
        ms_edit.enable();
        ms_edit.clear();
        $("#edit_new_tags button").attr("disabled", false);
        $("#edit_al_photos").html("");
        $("#edit_al_cover").html("");
        var link = "/admin/edit_al_" + $("select[name=edit_select] option:selected").val();
        $.get(
            link,
            function(data){
                var data_edit = [];
                var exist_tags = [];
                $.each(data.all_tags, function(index, tag){
                    data_edit.push(tag.tag);
                });
                $.each(data.tags, function(index, tag){
                    exist_tags.push(tag.tag);
                });
                ms_edit.setData(data_edit);
                if(exist_tags.length != 0)  ms_edit.setValue(exist_tags);


                for(var i = 0; i < data.photos.length; i++){
                    if(data.photos[i]['ph_name'].indexOf("cover") > 0){
                        $("<div class='edit_al_photo_preview'>Текущая обложка:</div>")
                            .append(
                                    $("<div><img src='/img/"+data.photos[i]["al_name"]+"/"+data.photos[i]["ph_name"]+"' width='50%'></div>")
                                )
                            .append("<button onclick=\"edit_new_cover()\">Новая обложка</button>")
                            .appendTo("#edit_al_cover");
                    }
                    else{
                        $("<div class='edit_al_photo_preview'></div>")
                            .append("<img src='/img/"+data.photos[i]["al_name"]+"/"+data.photos[i]["ph_name"]+"' data-al="+data.photos[i]["al_id"]+" data-ph="+data.photos[i]["ph_id"]+" width='150px'>")
                            .append("<div class='delete_one_photo'><div onClick='delete_one_photo($(this))' data-al="+data.photos[i]["al_id"]+" data-ph="+data.photos[i]["ph_id"]+">Удалить</div></div>")
                            .appendTo("#edit_al_photos");
                    }

                }
            }
        )
    })

    function edit_new_cover(){
        $("#edit_al_cover").html("");
        $(".delete_one_photo").children().each(function(){
            $(this).text("Выбрать");
            $(this).attr("onclick", "choose_as_new_cover($(this))");
        });
    }

    $("#edit_new_tags").on("submit", function(e){
        e.preventDefault();
        e.stopPropagation();

        var postData = $(this).serializeArray();
        postData.push({
            name:'_token',
            value:$('meta[name="csrf-token"]').attr('content')
        });
        postData.push({
            name:'album_id',
            value:$("select[name=edit_select] option:selected").val()
        });
        console.log(postData);
        $.post(
            "/admin/save_new_tags",
            postData,
            function(data){
                alert(data[1]);
            }
        )
    });

    function choose_as_new_cover(elem){
        //$('.work_with_cover').append('<img id="cover" src = "'+elem.find('img:first').attr('src')+'" width="100%">');
        $("<div class='edit_al_photo_preview'></div>")
            .append('<img id="edit_cover" src = "'+elem.parent().parent().find("img[data-al="+elem.data("al")+"][data-ph="+elem.data("ph")+"]").attr("src")+'" width="100%">')
            .append("<button id=\"save_new_cover_button\" onclick=\"save_new_cover("+elem.data("al")+")\">Сохранить</button>")
            .appendTo("#edit_al_cover");
        var img = document.getElementById("edit_cover");

        cropper = new Cropper(img,{
            viewMode: 3,
            aspectRatio: 1/1,
            dragMode: 'move',
            cropBoxResizable: true,
            ready(){
                cropper.crop();
                cropper.setCropBoxData({"width":480,"height":360});
            }
        })
    }

    function save_new_cover(album_id){
        var fd = new FormData();
        var imgData = cropper.getCroppedCanvas({width: 960, height:720}).toDataURL("image/jpeg", 1);
        var photo = imgData.replace("data:image/jpeg;base64,", "");
        fd.append('edit_cover', photo);
        fd.append("al_id", album_id);
        fd.append("al_name", $("select[name=edit_select] option:selected").data("al_name"));
        $.ajax({
            type: 'POST',
            url: "/admin/edit_al/new_cover",
            headers:{
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr("content")
            },
            data: fd,
            processData: false,
            contentType: false,
            success: function (data) {
                alert(data[0]);
                cropper.destroy();
                $("#save_new_cover_button").remove();
                $("#edit_cover").remove();
                //$("<button onclick=\"edit_new_cover()\">Новая обложка</button>").appendTo("#edit_al_cover .edit_al_photo_preview");
                console.log(data[1]);

                $('<img id="edit_cover" src = "'+data[1]+'" width="100%">').appendTo("#edit_al_cover .edit_al_photo_preview");
                $("<button onclick=\"edit_new_cover()\">Новая обложка</button>").appendTo("#edit_al_cover .edit_al_photo_preview");
                //$("#edit_cover").attr("src", data[1]);
            }
        })
    }

    function delete_one_photo(elem){
        var link = "/admin/delete/album_"+elem.data("al")+"/photo_"+elem.data("ph");
        if(confirm("ДЕйствительно удалить это фото?")){
            $.get(
                link,
                function(data){
                    $("div[data-al="+data[0]+"][data-ph="+data[1]+"]").parent().parent().remove();
                }
            )
        }
    };

    $("select[name=delete_select]").on("change", function(){
        $("#delete_al_photos").html("");
        var link = "/admin/delete_al/show_photo_" + $("select[name=delete_select] option:selected").val();
        $.get(
            link,
            function(data){
                for(var i = 0; i < data.length; i++){
                    $("<div class='delete_al_photo_preview'></div>")
                        .append("<img src='/img/"+data[i]["al_name"]+"/"+data[i]["ph_name"]+"' width='150px'>")
                        .appendTo("#delete_al_photos");
                }
            $("#delete_al_button").prop("disable", false);
            $("#delete_al_button").show();
            }
        )
    });

    $("#delete_al_button").on("click", function(){
        if(confirm("Действительно удалить альбом?")){
            var link = "/admin/delete_al_" + $("select[name=delete_select] option:selected").val();
            $.get(
                link,
                function(){
                    window.location.reload();
                }
            )
        }
    });

    $("button[data-action*=_al]").on("click", function(e){
        e.preventDefault();
        if($(this).data("action") == "edit_al"){
            ms_edit.clear();
            $("select[name=edit_select]").val(0)
        }
        $("div[class*='_al']").slideUp();
        $("."+$(this).data("action")).slideToggle();
    });


    $("#form_send_pic").on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
    }).on('drop', function(e) {
        $("#form_send_pic").removeClass("form_style").addClass("form_style_dropped");
        $('INPUT[id="pic"]')[0].files = e.originalEvent.dataTransfer.files;
        readURL(e.originalEvent.dataTransfer.files);
    });

    $('INPUT[type="file"]').change(function () {
        if($(this).prop("files").length == 0){
            $("#form_send_pic").addClass("form_style");
            $('#image_to_upload').empty();
        }
        else{
            readURL($('INPUT[id="pic"]').prop("files"));
        }

    });


    $("#form_send_pic").on("click", "*[data-click]", function(){
        $(".work_with_cover").html("");
        $(".work_with_cover").append('<img id="cover" src = "'+$(this).find('img:first').attr('src')+'" width="100%">');

        var img = document.getElementById("cover");

        cropper = new Cropper(img,{
            viewMode: 3,
            aspectRatio: 1/1,
            dragMode: 'move',
            cropBoxResizable: true,
            ready(){
                cropper.crop();
                cropper.setCropBoxData({"width":480,"height":360});
            }
        })
    });

    function readURL(files) {
        $('#image_to_upload').empty();
        var count = files.length;
        var name_ar = [];
        for(var i = 0; i<count; i++){
            name_ar = files[i].name.split(".");
            if($.inArray(name_ar[name_ar.length-1], ["jpeg", "jpg", "png"]) != -1){
                img_name(files[i], files[i].name, i);
            }
        }

    }

    function img_name(file, name, count) {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function (e) {
            $('<div id="parent'+count.toString()+'" class="col-md-3 my-auto" data-click=\"cover\"></div>').appendTo('#image_to_upload');
            $('#parent'+count.toString()).prepend('<img class="ready_to_upload" ' +
                'src="'+e.target.result+'" width="100%" />');
            /*$('#parent'+count.toString()).append('<input name="'+name+'" type="checkbox" ' +
                ' onchange="cover($(this))"/> Выбрать как обложку');*/
        }
    }

    $('#form_send_pic').on('submit', function (e) {
        e.preventDefault();
        if($(".work_with_cover").children().length == 0){
            alert("Вадик, конечно ты молодец, фотки загрузил. А обложку?)");
            return false;
        }
        if($("textarea[name=album_desc]").val().length > 255){
            alert("Вадик, очень большое описание. Надо поправить.");
            return false;
        }
        var _token = $('meta[name="csrf-token"]').attr('content');
        var fd = new FormData();
        var link = $(this).attr('action');
        var album_name = $('#name_of_album').val();
        var album_desc = $("textarea[name=album_desc]").val();
        for(var i=0; i < $('#pic').get(0).files.length; i++){
            fd.append('pic'+i.toString(), $('#pic').get(0).files[i]);
        }
        fd.append('name_of_album', album_name);
        fd.append('album_desc', album_desc);
        var tags = [];
        $("input[name^=tag]").each(function(){
            tags.push($(this).val());
        });
        var tags_str = tags.join('/');
        fd.append("tags", tags_str);

        var imgData = cropper.getCroppedCanvas({width: 960, height:720}).toDataURL("image/jpeg", 1);
        var test = imgData.replace("data:image/jpeg;base64,", "");
        fd.append('cover', test);
        // Display the key/value pairs
        for (var pair of fd.entries()) {
            console.log(pair[0]+ ', ' + pair[1]);
        }

        $.ajax({
            type: 'POST',
            url: link,
            headers:{
                'X-CSRF-TOKEN': _token
            },
            data: fd,
            processData: false,
            contentType: false,
            success: function (data) {
                alert(data[1]);
                $("#image_to_upload").html("");
                $(".work_with_cover").html("");
                $("#name_of_album").val("");
                $("#pic").val("");
                $('#form_send_pic').find("textarea:first").val("");
                ms.clear();
            }
        })


    })
</script>



</body>
</html>