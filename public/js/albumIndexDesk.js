$(window).on('load', function(){
    var ind = 0;
    var count = $("#photo_car").data("piccount");
    if($("#photo_car").find("img").length != 0){
          $("#photo_car").find("img").each(function(){
              var li = $("<li></li>").addClass("list-inline-item").attr("data-target", "#photo_car").attr("data-slide-to", ind).appendTo("#xMiniPic");
              var _img = new moxie.image.Image();
              _img.onload = function(){
                  this.embed(
                      li[0],
                      {
                          width:80,
                          height:60,
                          type:"image/jpeg",
                          quality:50,
                          crop:true
                      }
                  );
              };
              _img.load($(this).attr("src"));
              ind += 1;
              if(ind == parseInt(count)){
                  setTimeout(function(){
                      $('#before-load').find('i').fadeOut().end().delay(400).fadeOut('slow');
                  }, 800);
              }
          });
      }
      else{
          if(confirm("Альбом пуст. Перейти на главную страницу?")){
              window.location.href="/";
          }
      }
});

$(".carousel-image").on("click", function(){
    $(".carousel-control-next").click();
});

$("#forward").on("click", function(){
    var leftPos = $('.minipicul').scrollLeft();
    $(".minipicul").animate({scrollLeft: leftPos + 270});
});

$("#backward").on("click", function(){
    var leftPos = $('.minipicul').scrollLeft();
    $(".minipicul").animate({scrollLeft: leftPos - 270});
});
