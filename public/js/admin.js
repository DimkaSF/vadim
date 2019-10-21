var uploader;
var pics = [];
var ms, ms_editAl, ms_editPh, ms_editTags, ms_del;
var cropper;
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
                files = sortByKeyAsc(files, "name");
                var _list = $("#preview").find("tbody:first");
                $.each(files, function(index, file){
                    pics.push(file);
                    var _row = $("<tr></tr>")
                        .attr("id",file.id)
                        .data("id",file.id)
                        .attr("num", index)
                        .append(
                            $("<td></td>")
                        )
                        .append(
                            $("<td>"+file.name+"</td>")
                        )
                        .append(
                            $("<td>"+plupload.formatSize(file.size)+"</td>")
                        )
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
                    .appendTo(_list);
                    var _img = new moxie.image.Image();
                    _img.onload = function(){
                        this.embed(
                            _row.find(">td:first")[0],
                            {
                                width:50,
                                height:50,
                                type:"image/jpeg",
                                quality:100,
                                crop:true
                            }
                        );
                    };
                    _img.load(file.getSource());
                });
            }
        },
        headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}
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

    ms_del = $("input[name=delAl]").magicSuggest({
        placeholder: 'Выбери альбом',
        maxDropHeight: 145,
    });


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

$("#containerUploader").on("click", "*[class=upPic]", function(){
    var _row = $(this).closest("tr");
    var currentPic = uploader.getFile(_row.attr("id"));

    console.log(uploader.getFiles());
    _row.prev().insertAfter(_row);
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


$("#formSendPic").on("submit", function(e){
    e.preventDefault();
    e.stopPropagation();
    var albumId;
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
    console.log(postData);


    /*$.post(
        "/admin/createalbum",
        postData,
        function(data){
            if(data.result){
                albumId = data.id;
            }
        }
    );*/


    uploader.settings.multipart_params.id = 56;
    uploader.settings.multipart_params.albumName = "LLL";
    uploader.start();
});