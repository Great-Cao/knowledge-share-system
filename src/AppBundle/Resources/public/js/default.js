$(function(){
    $(".collect-btn").click(function(){
        var $btn = $(this);
        var img = $(this).data('img');
        var li = '<li><a style="margin-left: 10px;"><img class="img-circle img-sm" src="'+img+'" alt="User Image"></a></li>';
        var $collectNum = $btn.find(".collect-num").html();
        $.post($btn.data('url'),function(data){
            if (data.status == 'success') {
                $('#favorite-list').prepend(li);
                $collectNum = parseInt($collectNum)+parseInt(1);
                $btn.hide();
                $btn.parent().find(".uncollect-btn").show();
                $btn.parent().find(".uncollect-btn").find(".uncollect-num").html($collectNum);
            }  else {
                location.href = '/login';
            }
        })
    });

    $(".uncollect-btn").click(function(){
        var $btn = $(this);
        var $collectNum = $btn.find(".uncollect-num").html();
        $.post($btn.data('url'),function(data){
            if (data.status == 'success') {
                $('#favorite-list').children("li").eq(0).remove();
                $collectNum = parseInt($collectNum)-parseInt(1);
                $btn.parent().find(".collect-btn").find(".collect-num").html($collectNum);
                $btn.hide();
                $btn.parent().find(".collect-btn").show();
            } else {
                location.href = '/login';
            }
        })
    });

    $(".like-btn").click(function(){
        var $btn = $(this);
        var $collectNum = $btn.find(".like-num").html();
        var img = $(this).data('img');
        var li = '<li><a style="margin-left: 10px;"><img class="img-circle img-sm" src="'+img+'" alt="User Image"></a></li>';
        $.post($btn.data('url'),function(data){
            if (data.status == 'success') {
                $('#like-list').prepend(li);
                $collectNum = parseInt($collectNum)+parseInt(1);
                $btn.hide();
                $btn.parent().find(".dislike-btn").show();
                $btn.parent().find(".dislike-btn").find(".dislike-num").html($collectNum);
            } else {
                location.href = '/login';
            }
        })
    });

    $(".dislike-btn").click(function(){
        var $btn = $(this);
        var $collectNum = $btn.find(".dislike-num").html();
        $.post($btn.data('url'),function(data){
            if (data.status == 'success') {
                $('#like-list').children("li").eq(0).remove();
                $collectNum = parseInt($collectNum)-parseInt(1);
                $btn.parent().find(".like-btn").find(".like-num").html($collectNum);
                $btn.hide();
                $btn.parent().find(".like-btn").show();
            } else {
                location.href = '/login';
            }
        })
    });

    $(".delete-favorite").click(function(){
        var url = $(this);
        if (confirm('确定要取消收藏吗?')) {
            $.post(url.data('url'),function(data){
                if (data.status == 'success') {
                    location.reload();
                }
            })
        }
    });
});