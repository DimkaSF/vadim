$(function(){
    $(".footer").find("div[class$=links]").addClass("justify-content-between");
});
$(window).on('load', function(){
    $('#before-load').find('i').fadeOut().end().delay(400).fadeOut('slow');
    $('#slide_down').slideDown(1000);
});