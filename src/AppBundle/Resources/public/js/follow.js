$(function(){
    $("#topic-tables #follow-btn").click(function(){
        var $btn = $(this);
        var $followNum = $btn.find("span").html();
        $.post($btn.data('url'),function(data){
            $followNum = parseInt($followNum)+parseInt(1);
            $btn.next().find("span").html($followNum);
            $btn.hide();
            $btn.next().show();
        })
    });

    $("#topic-tables #unfollow-btn").click(function(){
        var $btn = $(this);
        var $followNum = $btn.find("span").html();
        $.post($btn.data('url'),function(data){
            $followNum = parseInt($followNum)-parseInt(1);
            $btn.prev().find("span").html($followNum);
            $btn.hide();
            $btn.prev().show();
        })
    });

    $('#unfollow-user-btn').on('click',function(){
        var $btn = $(this);
        $.post($btn.data('url'),function(){
            $btn.hide();
            $('#follow-user-btn').show();
        });
    });
    $('#follow-user-btn').on('click',function(){
        var $btn = $(this);
        $.post($btn.data('url'),function(){
            $btn.hide();
            $('#unfollow-user-btn').show();
        });
    });
})
