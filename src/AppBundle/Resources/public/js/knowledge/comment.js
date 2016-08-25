
$(document).ready(function(){
    $("#comment").click(function(){
        var value = $('[name = comment]').val();
        var id = $(this).data('id');
        $.ajax({
            url:$(this).data('url'),
            data:{comment:value,knowledgeId:id},
            type:"POST",
            success:function(data){
                if (empty(data)) {
                    location.href = '/login';
                }
            }
        })
    });
});