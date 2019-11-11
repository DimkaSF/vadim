var uploader, uploaderEdit;
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
                        .attr("data-type", "add")
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
                if(_list.find(".rowInfo")){
                    _list.find(".rowInfo").remove();
                };
                _list.append(
                    $("<tr class=\"rowInfo\" style=\"border:1px solid black\"></tr>")
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
                editCont.find("form:first").find("#pickFiles").attr("id", "pickFilesEdit");
                editCont.find("form:first").find("#containerUploader").attr("id", "containerUploaderEdit");
                editCont.find(".col-md-6:eq(1)").removeClass("pt-5");
                editCont.find("form:first").attr("id", "formSendPicEdit");
                editCont.find(".workWithCover:first").attr("class", "workWithCoverEdit");
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
                        .attr("data-type", "edit")
                        .attr("data-id", filename[1])
                        .attr("data-path", "/img/"+data.al_info.slug+"/"+filename[0])
                        .append("<td></td>")

                        .append("<td>"+filename[0]+"</td>")
                        .append(
                            $("<td></td>")
                                .append(
                                    $("<i class=\"fa fa-times delPhoto\" data-new\"false\"></i>")
                                        .css("cursor", "pointer")
                                )
                        )
                        .append(
                            $("<td class=\"onCoverEdit\">На обложку</td>")
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

                /*Работа с обложкой*/
                $("<div class='edit_al_photo_preview'>Текущая обложка:<br/></div>")
                    .append("<img src=\"/img/"+data.al_info.slug+"/"+allPhotosInfo[0].split(",")[0]+"\" width='50%'>")
                .appendTo(parent.find(".workWithCoverEdit:first"));
                /*закончили работать с обложкой*/

                /*Создаём новый uploader*/
                pics=[];
                uploader = new plupload.Uploader({
                    browse_button:parent.find("#pickFilesEdit")[0],
                    container:parent.find("#containerUploaderEdit")[0],
                    drop_element:parent.find("#containerUploaderEdit")[0],
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
                            var _list = parent.find("#preview").find("tbody:first");
                            $("#uploadProgress").text("Создаём очередь. Ожидайте.");
                            $.each(files, function(index, file){
                                pics.push(file);
                                var _row = $("<tr></tr>")
                                    .attr("data-type", "edit")
                                    .attr("data-id", file.id)
                                    .append("<td></td>")
                                    .append("<td style=\"word-break: break-all;\">"+file.name+"</td>")
                                    .append(
                                        $("<td></td>")
                                            .append(
                                                $("<i class=\"fa fa-times delPhoto\" data-new=\"true\"></i>")
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
                        },
                        UploadComplete:function(up, files){
                            sendDataAfterEdit();
                            $("#before-load").fadeOut(1500);
                        },
                        FileUploaded:function(up, file, result){
                            var response = $.parseJSON(result.response);

                            if(response.result){
                                $("#containerUploaderEdit").find("table:first>tbody:first").find("tr[data-id="+file.id+"]").attr("data-id", response.content.id)
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
                /*создали новый uploader*/

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
    console.log(albumsNames);

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
            window.location.reload(true);
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
    if(id == $(".workWithCover").find("img:first").data("id")){
        cropper.destroy();
        $(".workWithCover").find("img:first").remove();
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

$("body").on("click", "*[class=onCover]", function(){
    var _row = $(this).closest("tr");
    var id = _row.data("id");
    var ind = 0;
    var selector="";
    for(var i=0, len=pics.length; i<len; i++){
        if(pics[i]["id"] == id){
            ind = i;
            break;
        }
    }
    if(_row.data("type") == "edit"){
        selector = "Edit";
    }
    $(".workWithCover"+selector).html("");

    var _parent = $(".workWithCover"+selector);
    var reader = new FileReader();
    reader.readAsDataURL(pics[ind].getNative());


    reader.onload = function (e) {
        _parent.append("<img id=\"myCover\" src=\""+e.srcElement.result+"\" width=\"100%\" data-id=\""+pics[ind]["id"]+"\" />");
    };
    reader.onloadend = function(){
        var cover = _parent.find("#myCover")[0];
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


$("#editContainer").on("click", ".onCoverEdit", function(){
    var _cont = $(".workWithCoverEdit>div:first");
    var parent = $(this).closest("tr");
    var id = parent.data("id");
    _cont.html("");
    var _img = $("<img src=\""+parent.data("path")+"\" width=\"100%\" data-id=\""+id+"\" />");

    _img.appendTo(_cont);

    cropper = new Cropper(_img[0],{
        viewMode: 3,
        aspectRatio: 1/1,
        dragMode: 'move',
        cropBoxResizable: true,
        ready(){
            cropper.crop();
            cropper.setCropBoxData({"width":920,"height":720});
        }
    });
})

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


$("#editContainer").on("click", "form:first input[type=submit]", function(e){
    e.preventDefault();
    e.stopPropagation();
    var id = ms_edit.getSelection()[0].id;
    var slug = ms_edit.getSelection()[0].slug;

    if(uploader.files.length != 0){
        uploader.settings.multipart_params.id = id;
        uploader.settings.multipart_params.albumName = slug;
        uploader.start();
    }
    else{
        sendDataAfterEdit();
    }


})

function sendDataAfterEdit(id = null, slug = null){
    if($.type(id) == "null" && $.type(slug) == "null"){
        id = ms_edit.getSelection()[0].id;
        slug = ms_edit.getSelection()[0].slug;
    }
    var _tokenVal = $('meta[name=csrf-token]').attr("content");
    var newOrder = [];
    var postData = $("#editExist").find("form:first").serializeArray();
    $("#containerUploaderEdit>table:first>tbody:first").find("tr").each(function(){
        newOrder.push($(this).data("id"));
    });

    postData.push({
        name:"id",
        value:id
    });
    postData.push({
        name:"pos",
        value:newOrder
    });
    postData.push({
        name:"_token",
        value:_tokenVal
    });

    if($.type(cropper) != "undefined"){
        cropper.getCroppedCanvas().toBlob(function (blob) {
            var formData = new FormData();
            formData.append("edit_cover", blob);
            formData.append("slug", slug);
            formData.append("al_id", id);
            formData.append("_token", _tokenVal);
            $.ajax('/admin/savenewcover', {
              method: "POST",
              data: formData,
              processData: false,
              contentType: false,
            });
        });
    }

    $.post(
        "/admin/saveafteredit",
        postData,
        function(data){
            if(data.result){
                $("#finalDialog").dialog( "open" );
            }
            else{
                console.log(data.errors);
            }
        }
    );
}

$("#editContainer").on("click", ".delPhoto", function(){

    if($(this).data("new")){
      var id = $(this).closest("tr").data("id");
      for(var i=0, len=pics.length; i<len; i++){
          if(pics[i]["id"] == id){
              uploader.removeFile(pics[i]);
              break;
          }
      }
      if(id == $(".workWithCover").find("img:first").data("id")){
          cropper.destroy();
          $(".workWithCover").find("img:first").remove();
      }

      $(this).closest("tr").remove();
    }
    else{
      if(confirm("Действительно удалить это фото?")){
          var parent = $(this).closest("tr");
          var phId = parent.data("id");
          var postData = [];
          postData.push({
              "name":"alId",
              "value":ms_edit.getSelection()[0].id
          });
          postData.push({
              "name":"phId",
              "value":phId
          });
          postData.push({
              name:"_token",
              value:$('meta[name=csrf-token]').attr("content")
          });

          $.post(
              "/admin/deletephoto",
              postData,
              function(data){
                  if(data.result){
                      parent.remove();
                      if($(".workWithCoverEdit").find("img:first").data("id") == data.content.phId){
                          cropper.destroy();
                          $(".workWithCoverEdit").find("img:first").remove();
                      }
                  }
              }
          );
      }
    }


});
