var uploader;
var pics = [];
var ms, ms_del, ms_edit;
var cropper;
var picCount=0;

$(function(){
    uploader = new plupload.Uploader({
        browse_button:$("#pickFiles")[0],
        container:$("#containerUploader")[0],
        drop_element:$("#containerUploader")[0],
        max_retries:0,
        sortable: true,
        filters:{
            max_file_size:"16mb",
            mime_types:"image/jpg,image/jpeg,image/png",
            prevent_duplicates:true
        },
        multipart_params:{
            "empty":"true"
        },
        url:"/admin/savephoto",
        init:{
            FilesAdded:function(up, files){
                $("#before-load").fadeIn(1500);
                files = sortByKeyAsc(files, "name");
                console.log(files);
                var _list = $("#preview").find("tbody:first");
                $("#uploadProgress").text("Создаём очередь. Ожидайте.");
                $.each(files, function(index, file){
                    pics.push(file);
                    var _row = $("<tr></tr>")
                        .attr("id",file.id)
                        .data("id",file.id)
                        .attr("data-name", file.name)
                        .append("<td></td>")
                        .append("<td style=\"word-break: break-all;\">"+file.name+"</td>")
                        .append("<td>"+plupload.formatSize(file.size)+"</td>")
                        .append(
                            $("<td></td>")
                                .append(
                                    $("<i class=\"fa fa-times delPhoto\"></i>")
                                        .css("cursor", "pointer")
                                )
                        )
                        .append(
                            $("<td class=\"onCover\">На обложку</td>")
                                .css("cursor", "pointer")
                        )
                        .append(
                            $("<td data-move=\"up\"><i class=\"fa fa-arrow-up\" aria-hidden=\"true\"></i></td>")
                                .css("cursor", "pointer")
                        )
                        .append(
                            $("<td data-move=\"down\"><i class=\"fa fa-arrow-down\" aria-hidden=\"true\"></i></td>")
                                .css("cursor", "pointer")
                        )
                    .appendTo(_list);
                    var _img = new moxie.image.Image();
                    _img.onload = function(){
                        this.embed(
                            _row.find(">td:first")[0],
                            {
                                width:50,
                                height:50,
                                type:"image/jpeg",
                                quality:50,
                                crop:true
                            }
                        );
                        if(this.name == pics[pics.length-1].name){
                            $("#before-load").fadeOut(1500);
                            $("#uploadProgress").text("");
                        }
                    };
                    _img.load(file.getSource());
                });
                _list.append(
                    $("<tr style=\"border:1px solid black\"></tr>")
                        .append("<td colspan=\"3\">Всего файлов : "+files.length+"</td>")
                        .append("<td colspan=\"4\">Общий объём : "+plupload.formatSize(up.total.size)+"</td>")
                )
            },
            UploadComplete:function(up, files){
                $("#before-load").fadeOut(1500);
                $("#finalDialog").dialog( "open" );
            },
            FileUploaded:function(up, file, result){
                if(result.response){
                    $("#uploadProgress").text("").text(file.name + " загружен. Осталось: " + picCount);
                    picCount = picCount - 1;
                }
                else{
                    $("#uploadProgress").text("").text(file.name + " - тут ошибка...что то пошло не так");
                }
            }
        },
        headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
    });
    uploader.init();


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
    ms = $("#formSendPic input[name=tags]").magicSuggest({
        placeholder: 'Выбери тег',
        maxDropHeight: 145,
        noSuggestionText: 'Такого тега ещё не было.'
    });
    ms.setData(tags);

    var albumsNames = [];
    $.ajax({
        type: 'GET',
        url: "/admin/getalbmsnames",
        headers:{
            'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr("content")
        },
        processData: false,
        contentType: false,
        success: function (data) {
            $.each(data.content, function(index, al){
                albumsNames.push(al);
            });
        }
    });
    //////////////////////////
    ms_edit = $("input[name=editAl]").magicSuggest({
        placeholder: 'Выберите альбом для редактирования',
        maxDropHeight: 145,
        maxSelection: 1
    });
    $(ms_edit).on("selectionchange", function(e,m){
        var editCont = $("#editContainer");
        if($.type(this.getValue()[0]) == "undefined"){
            editCont.find("#addContainer").remove();
            return false;
        }

        $('#before-load').fadeIn();
        $.get(
            "/admin/edit_al_"+this.getValue()[0],
            function(data){
                console.log(data);
                editCont.append($("#addContainer").clone());
                editCont.find(".add_al:first").attr("id", "editExist");
                var parent = editCont.find("#editExist");

                parent.find("input[name=nameOfAlbum]").val(data.al_info.al_name);
                parent.find("textarea[name=albumDesc]").val(data.al_info.al_desc);

                parent.find(".ms-ctn:first").remove();
                $("<input name=\"edit_alInside\" type=\"text\">").insertAfter(parent.find("#editMagicSuggestDropAfter"));
                var ms_editInside = parent.find("input[name=\"edit_alInside\"]").magicSuggest({
                    placeholder: 'Выбери тег',
                    maxDropHeight: 145
                });
                var msData = [];
                $.each(data.all_tags, function(index, tag){
                    msData.push(tag.tag);
                });
                ms_editInside.setData(msData);
                ms_editInside.setValue(data.al_info.tags.split(","), true);

                /*Работа с таблицей*/
                parent.find()
                parent.find("table:first>thead:first").find("tr").remove();
                $("<tr></tr>")
                    .append("<td>Картинка</td>")
                    .append("<td>Номер</td>")
                    .append("<td>Имя</td>")
                    .append("<td>Удалить</td>")
                    .append("<td></td>")
                    .append("<td></td>")
                    .append("<td></td>")
                .appendTo(parent.find("table:first>thead:first"));

                var allPhotosInfo = data.al_info.photos.split(";");
                var lastPicName = allPhotosInfo[allPhotosInfo.length-2].split(",")[0];
                var _list = parent.find("table:first>tbody:first");
                $.each(allPhotosInfo.slice(1), function(index, photoinfo){
                    var filename = photoinfo.split(",");
                    var _row = $("<tr></tr>")
                        .attr("data-pos", filename[2])
                        .append("<td></td>")
                        .append("<td>"+filename[2]+"</td>")
                        .append("<td>"+filename[0]+"</td>")
                        .append(
                            $("<td></td>")
                                .append(
                                    $("<i class=\"fa fa-times delPhoto\"></i>")
                                        .css("cursor", "pointer")
                                )
                        )
                        .append(
                            $("<td data-move=\"up\"><i class=\"fa fa-arrow-up\" aria-hidden=\"true\"></i></td>")
                                .css("cursor", "pointer")
                        )
                        .append(
                            $("<td data-move=\"down\"><i class=\"fa fa-arrow-down\" aria-hidden=\"true\"></i></td>")
                                .css("cursor", "pointer")
                        )
                    var _img = new moxie.image.Image();
                    _img.onload = function(){
                        this.embed(
                            _row.find(">td:first")[0],
                            {
                                width:50,
                                height:50,
                                type:"image/jpeg",
                                quality:50,
                                crop:true
                            }
                        );

                        if(lastPicName == filename[0]){
                            $('#before-load').fadeOut();
                        }
                    };
                    _img.load("/img/"+data.al_info.slug+"/"+filename[0]);
                    _row.appendTo(_list);
                });

                /*закончили работать с таблицей*/

                /*Работа с обложкой
                var reader = new FileReader();
                console.log("/img/"+data.al_info.slug+"/"+allPhotosInfo[0].split(",")[0]);
                reader.readAsDataURL("/img/"+data.al_info.slug+"/"+allPhotosInfo[0].split(",")[0]);
                reader.onload = function (e) {
                    parent.find(".workWithCover").append("<img id=\"myCoverEdit\" src=\""+e.srcElement.result+"\" />");
                };
                reader.onloadend = function(){
                    var cover = parent.find(".workWithCover").find("#myCoverEdit")[0];
                    cropper = new Cropper(cover,{
                        viewMode: 3,
                        aspectRatio: 1/1,
                        dragMode: 'move',
                        cropBoxResizable: true,
                        ready(){
                            cropper.crop();
                            cropper.setCropBoxData({"width":920,"height":720});
                        }
                    });
                };

                /*закончили работать с обложкой*/

            }
        );
    });
    ////////////////////////


    ms_del = $("input[name=delAl]").magicSuggest({
        placeholder: 'Выберите альбом',
        maxDropHeight: 145,
        maxSelection: 1
    });
    $(ms_del).on('selectionchange', function(e,m){
        if(confirm("Точно удалить этот альбом?")){
            $.ajax({
                type: 'GET',
                url: "/admin/delete_al_"+this.getValue()[0],
                headers:{
                    'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr("content")
                },
                processData: false,
                contentType: false,
                success: function (data) {
                    if(data.result){
                        alert("Удаление прошло успешно!");
                        window.location.reload();
                    }
                }
            });
        }
    });
    ms_edit.setData(albumsNames);
    ms_del.setData(albumsNames);
    $( "#tabs" ).tabs();

});



$("#finalDialog").dialog({
    resizable: false,
    height: "auto",
    width: 400,
    modal: true,
    draggable: false,
    autoOpen: false,
    dialogClass: "no-close",
    buttons: {
        "На сайт": function() {
            window.location.href = "/";
        },
        "Остаться в админке": function() {
            window.location.reload();
        }
    }
});



function sortByKeyAsc(array, key) {
    return array.sort(function (a, b) {
        var x = a[key]; var y = b[key];
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
}

$("#buttonsContainer").on("click", "*[data-action]",function(e){
    e.preventDefault();
    $("div[class$=\"_al\"]").slideUp();
    $("."+$(this).data("action")).slideToggle();
});

$("#containerUploader").on("click", "*[class$=delPhoto]", function(){
    var id = $(this).closest("tr").data("id");
    for(var i=0, len=pics.length; i<len; i++){
        if(pics[i]["id"] == id){
            uploader.removeFile(pics[i]);
            break;
        }
    }
    $(this).closest("tr").remove();
});

$("body").on("click", "*[data-move]", function(){
    var _row = $(this).closest("tr");
    var direction = $(this).data("move");
    switch(direction){
        case "up":{
            _row.prev().insertAfter(_row);
            break;
        }
        case "down":{
            _row.insertAfter(_row.next());
            break
        }
    }

});

$("#containerUploader").on("click", "*[class=onCover]", function(){
    $(".workWithCover").html("");
    var id = $(this).closest("tr").data("id");
    var ind = 0;
    for(var i=0, len=pics.length; i<len; i++){
        if(pics[i]["id"] == id){
            ind = i;
            break;
        }
    }
    var _img = pics[ind].getNative();
    var reader = new FileReader();
    reader.readAsDataURL(_img);
    reader.onload = function (e) {
        $(".workWithCover").append("<img id=\"myCover\" src=\""+e.srcElement.result+"\" />");
    };
    reader.onloadend = function(){
        var cover = $(".workWithCover").find("#myCover")[0];
        cropper = new Cropper(cover,{
            viewMode: 3,
            aspectRatio: 1/1,
            dragMode: 'move',
            cropBoxResizable: true,
            ready(){
                cropper.crop();
                cropper.setCropBoxData({"width":920,"height":720});
            }
        });
    };
});

$("textarea[name=albumDesc]").on("keyup", function(){
    if($(this).val().length <= 255){
        $(".helper").text("Осталось символов:" + (255-$(this).val().length));
    }
    else{
        $(".helper").text("Вадик, слишком много символов. Крактость - сестра  таланта :)");
    }
});

$("#exit").on("click", function(e){
    e.preventDefault();
    window.location.href = "/";
});

$("#formSendPic").on("submit", function(e){
    e.preventDefault();
    e.stopPropagation();
    if(!cropper){
        alert("Обложка не выбрана.");
        return false;
    }
    $("#before-load").fadeIn(1500);
    var albumId, albumName;
    var order = [];
    var postData = $(this).serializeArray();

    var imgData = cropper.getCroppedCanvas({width: 960, height:720}).toDataURL("image/jpeg", 1);
    var imgSend = imgData.replace("data:image/jpeg;base64,", "");

    postData.push({
        name:"cover",
        value:imgSend
    });
    postData.push({
        name:"_token",
        value:$('meta[name="csrf-token"]').attr('content')
    });
    $("#containerUploader>table:first>tbody:first").find("tr").each(function(){
        order.push($(this).data("name"));
    });
    $.post(
        "/admin/createalbum",
        postData,
        function(data){
            if(data.result){
                albumId = data.content.id;
                albumName = data.content.albumName;
                $("#containerUploader>table:first>tbody:first").find("tr").each(function(){
                    order.push($(this).data("name"));
                });
                picCount = pics.length;
                uploader.settings.multipart_params.id = albumId;
                uploader.settings.multipart_params.albumName = albumName;
                uploader.settings.multipart_params.order = order;
                uploader.start();
            }
            else{
                alert("Загрузка альбом прошла неудачно.");
            }
        }
    );



});