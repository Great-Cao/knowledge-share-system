$(document).ready(function(){
    $('.pull-left').on('click','#user-setting',function(){
        var url = $(this).data('url');
        $.get(url,function(data){
            $("#uploadModal").html(data).modal();
        });
    });

    $('div').on('change','#inputImage',function(){
        var picture = document.getElementById('inputImage');
        var info = document.getElementById('tip-message');
        var preview = document.getElementById('picture-preview');
        if (!picture.value) {
            info.innerHTML = '没有选择文件';
            return false;
        }
        if (picture.files && picture.files[0]) {
            var file = picture.files[0];   
            if (file.size >= 2097152) {
                alert('图片不能大于2M');
                return false;
            }
            if (file.type!=='image/jpeg'&&file.type!=='image/png'&&file.type!=='image/gif') {
                alert('不是有效的图片文件');
            }
            var reader = new FileReader();
            reader.onload = function(e){
                var data = e.target.result;
                preview.innerHTML = '<img src="' + data + '" />';
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '<div class="img" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image,src=\'' + picture.value + '\'"></div>';
        }

    });

    $('#uploadModal').on('click','#upload-picture-btn', function(){
        var modal = $('#upload-picture-form').parents('.modal');
        var form = $('#upload-picture-form');
        var url = form.attr('action');
        var data = new FormData($('#upload-picture-form')[0]);

        $('#upload-picture-btn').button('submiting').addClass('disabled');
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            dataType: 'JSON', 
            cache: false, 
            processData: false, 
            contentType: false,
            success:function(data){
                modal.modal('hide');
                $('#user-image').attr('src',data.imageUrl);
                location.href = '/';  
            }
        });
        window.location.reload();
    });
});