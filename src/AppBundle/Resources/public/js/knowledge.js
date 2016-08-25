$("#learn-btn").click(function(event) {
    var $btn = $(this);
    $.post($btn.data('url'), function(data) {
        if (data.status == 'success') {
            $btn.hide();
            $btn.parent().find('#finish-btn').show();
        } else if (data.status == 'false') {
            location.href = '/login';
        }
    });
});