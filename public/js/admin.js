var uploader;
var pics = [];
$(function(){
    $(".add_al").hide();
    uploader = new plupload.Uploader({
        browse_button:$("#pickFiles")[0],
        container:$("#containerUploader")[0],
        drop_element:$("#containerUploader")[0],
        max_retries:0,
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
                            $("<td class=\"cover\">На обложку</td>")
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

$("#containerUploader").on("click", "*[class=cover]", function(){
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
        $(".workWithCover").append("<img src=\""+e.target.result+"\" width=\"100%\" />")
    }

});

$("#formSendPic").on("submit", function(e){
    e.preventDefault();
    e.stopPropagation();
    var postData = $(this).serializeArray();

    uploader.settings.multipart_params.id = 1;
    console.log(uploader);
    uploader.start();
});