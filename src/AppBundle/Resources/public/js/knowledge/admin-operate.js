$(document).ready(function(){
    $('span').on('click','.admin-delete-btn',function() {
        var url = $(this).data('url');
        if (confirm('确定要删除吗？')) {    
            $.post(url,function(data){  
                location.href = '/';                
            });
            location.reload();
        }
    });

    $('span').on('click','.admin-edit-btn',function(){
        var url = $(this).data('url');
        $.get(url,function(data){
            $('#uploadModal').html(data).modal();
        })
    })

    $('#uploadModal').on('click','#admin-edit-btn',function(){
        var modal = $('#admin-edit-form').parent('.modal');
        var form = $('#admin-edit-form');
        var url = form.attr('action');
        $('#admin-edit-btn').button('submiting').addClass('disabled');
        $.post(url,form.serialize(),function(data){
            modal.modal('hide');
            location.reload();
        })
    });
})
