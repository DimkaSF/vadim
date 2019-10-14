$(function(){
    $(".add_al").hide();
    $("#uploader").plupload({
        views:{list:false, thumbs:true},
        sortable:true
    });
});

$("#buttonsContainer").on("click", "*[data-action]",function(e){
    e.preventDefault();
    $("div[class$=\"_al\"]").slideUp();
    $("."+$(this).data("action")).slideToggle();
});

$("body").on("click","*[class=\"plupload_file ui-state-default plupload_delete ui-sortable-handle\"]",function(){
    $(this).bind("click", test());
});

function test(){
    console.log("la");
}