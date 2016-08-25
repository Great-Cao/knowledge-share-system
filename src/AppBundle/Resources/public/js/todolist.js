$(document).ready(function(){
    $('div.news-list #create-todo-btn').click(function(){
        var btn = $(this);
        $.post(btn.data('url'), function(data,status) {
            if (data == false) {
                location.href = "/login";
            } else if (data == true) {
                var parent = btn.parents('.news-list');
                parent.fadeTo('slow', 0.01, function(){
                    $(this).slideUp('slow', function(){
                        $(this).remove();
                    });
                });                
            }
        })
    });

    $('div.news-list #delete-todo-btn').click(function(){
        var btn = $(this);
        $.post(btn.data('url'), function(data){
            
        })
    });
})