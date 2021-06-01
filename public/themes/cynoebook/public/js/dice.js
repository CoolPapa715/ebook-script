    var o = [],
        e = $("#dice__cube"),
        i =
            1e3 *
            e
                .css("transition-duration")
                .split(",")[0]
                .replace(/[^-\d\.]/g, "");

    function n(a) {
        var t = e.attr("class").split(" ")[0],
            s = "show-" + a;
        e.removeClass(),
            t == s
                ? (e.addClass(s + " show-same"),
                  setTimeout(function () {
                      e.removeClass("show-same");
                  }, i))
                : e.addClass(s),
                
            o.push(a);
            
    }
    $("#dice__btn").on("click ", function () {
  
            var value;
            var a,
                t,
            
                o = ((a = 1), (t = 6), Math.floor(Math.random() * t + a));
            1 == o ? n("front") : 2 == o ? n("back") : 3 == o ? n("right") : 4 == o ? n("left") : 5 == o ? n("top") : 6 == o && n("bottom");
            
            switch (o) {
                case 1:
                    value = 1;
                    break;
                case 2:
                    value = 6;
                    break;
                case 3:
                    value = 4;
                    break;
                case 4:
                    value = 3;
                    break;
                case 5:
                    value = 2;
                    break;
                default:
                    value = 5;
                    break;
            }
    
            if(value%2==0){
                $("#is_even").remove();
            }else{
                $("#is_odd").remove();
            }
            $("#dice__btn").remove();
            $("#Dice_link").css("display",'block');
    });

