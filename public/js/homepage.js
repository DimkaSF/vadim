$(window).on('load', function () {
    if($(window).width() > 425){
        var items = [2, 3, 5, 6];
        var grid = items[Math.floor(Math.random()*items.length)];
        $("<style></style>")
            .prop("type", "text/css")
            .html("\
            .all_father div:nth-child("+grid+"n) {\
                grid-column:span 2;\
                grid-row: span 2;\
            }")
        .appendTo("head");
        $(".content").find(".row:first").addClass("all_father");
        $(".content").find(".row:first").find("div[class=setcss]").each(function(){
            $(this).removeClass("setcss");
            $(this).addClass("small parent");
        });
    }
    else{
        $(".content").find(".row:first").find("div[class=setcss]").each(function(){
            $(this).removeClass("setcss");
            $(this).addClass("small col-12");
            $(this).css("padding-left", "0").css("padding-right", "0");
            $(this).find("img:first").css("width", "100%");
        });
    }

    var colorThief = new ColorThief();
    $(".content").find(".row:first").find("div[class^=small]").each(function(){
        var res = colorThief.getColor($(this).find("img")[0]);
        $(this).find("div[class=overlay]").css("background", "rgb("+res[0]+", "+res[1]+", "+res[2]+", 0.8)");
    });
});