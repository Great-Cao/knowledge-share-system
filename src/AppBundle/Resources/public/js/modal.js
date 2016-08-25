$(document).ready(function(){
    $("body").on('click', '#addLink', function() {
        //没用serialize()是因为没想到比较好的解决办法
        var $url = $(this).data('url');
        var content = $('[name = content]').val();
        var title = $('[name = title]').val();
        var type = $('[name = type]').val();
        var summary = $('[name = summary]').val();
        var topic = $('[name = topic]').val();
        var tag = $('[name = tag]').val();

        if (checkNull('title','标题') == false) {
            return ;
        }
        if (isNum('select-topic', '主题') == false) {
            return ;
        }
        if (checkLength('select-topic') == false) {
            return;
        }

        $.ajax({
            url:$url,
            data:{content:content,title:title,type:type,summary:summary,topic:topic,tag:tag},
            type:"POST",
            success:function(data){
                location.href = '/';
            },
            error:function(jqXHR){
                alert("添加失败！");
            }
        });
    });

    $("body").on('click', '#addFile', function() {
        var $url = $(this).data('url');
        var tag = $('[name = tag]').val();
        var $file = new FormData($('#addFileForm')[0]);
        $file.append('tag',tag);
        if (checkFileSize('inputfile') == false) {
            return ;
        }
        if (checkNull('title','标题') == false) {
            return ;
        }
        if (isNum('select-topic', '主题') == false) {
            return ;
        }
        if (checkLength('select-topic') == false) {
            return ;
        }
        
        $.ajax({
            url:$url,
            cache:false,
            data:$file,
            type:"POST",
            async:false,
            processData:false,
            contentType:false,
            success:function(data){
                location.href = '/';
            },
            error:function(jqXHR){
                alert("添加失败！");
            }
        })
    });

    $(".news-list span .fa-edit").click(function(event){
        var $url = $(event.target).attr("data-url");
        $.get($url,function(data){
            $("#uploadModal").html(data).modal();
        });
    });

    $("#docModal").click(function(event){
        var $url = $(event.target).attr("data-url");
        $.get($url, function(data){
            $("#uploadModal").html(data).modal();
        });
    });

    $("#linkModal").click(function(event){
        var $url = $(event.target).attr("data-url");
        $.get($url, function(data){
            $("#uploadModal").html(data).modal();
        });
    });

    //自动读取标题
    $('body').on('input', '#inputlink', function() {
        var link = $(this).val();
        var url = $(this).data('url');
        $.ajax({
            url : url,
            data : { link : link },
            type : 'POST',
            success :function(data){
                $('#title').val(data.title);
            },
            error : function (data) {
                $('#title').val('读取标题失败,请手动填写标题');
            }
        })
    });

    //上传文件
    $('body').on('change', '#inputfile', function() {
        var fileInput = document.getElementById('inputfile');
        //检测是否选择文件
        if (!fileInput.value) {
            $("#title").val('请上传文件');
            return;
        }
        //获取文件相关信息
        var file = fileInput.files[0];
        var fileName = file.name;
        var fileSize = file.size;
        var maxSize = 20971520;
        if (fileSize >= maxSize) {
            $("#title").val('文件不能大于20M');
            return;
        }
        $("#title").val(fileName);
    });

    //检索主题
    $('body').on('input', '#topic', function() {
        var link = $(this).val();
        var url = $(this).data('url');
        $.ajax({
            url : url,
            data : { link : link },
            type : 'POST',
            success :function(data){
                $('#title').val(data.title);
            },
            error : function (data) {
                $('#title').val('读取标题失败,请手动填写标题');
            }
        })
    });

    $('.row').on('click','.delete-btn',function() {
        var url = $(this).data('url');
        if (confirm('确定要删除吗？')) {    
            $.post(url,function(data){  
                window.location.reload();  
            });
        }
    });

    $('#uploadModal').on('click','#knowledge-edit-btn', function(){
        var modal = $('#knowledge-edit-form').parents('.modal');
        var form = $('#knowledge-edit-form');
        var url = form.attr('action');
        $('#knowledge-edit-btn').button('submiting').addClass('disabled');
        $.post(url,form.serialize(), function(){
            modal.modal('hide');
            window.location.reload();
        });
    });

});

function checkNull(obj, vline){
    //判断输入框是否为空，为空时弹出提示框
    var value=document.getElementById(obj).value;
    value = value.replace(/(^\s*)|(\s*$)/g,"");///去除空格的方法
    if (value.length == 0) {
        alert(vline + " 输入值为空！");
        return false;
    }
    return true;
}

function isNum(obj, vid){
    var value=document.getElementById(obj).value;
    value = value.replace(/(^\s*)|(\s*$)/g,"");///去除空格的方法
    if (value.length == 0) {
        return true;
    }
    re = new RegExp("[^0-9]");
    var s;
    var i_value = document.getElementById(obj).value;
    if (s = i_value.match(re)) {
        return true;
    }
    alert("'" + vid + "' 中不能全为数字 '");
    return false;
}

function checkLength(obj) {
    var str=document.getElementById(obj).value;
    var realLength = 0;
    var charCode = -1;
    for (var i = str.length - 1; i >= 0; i--) {
        charCode = str.charCodeAt(i);
        if (charCode >=0 && charCode <=128 )　{
            realLength += 1;
        } else {
            realLength += 3;
        }
    }
    if (realLength > 60) {
        alert('长度不能超过２０个字');
        return false;
    }
    return true;
}

function checkFileSize(obj) {
    var fileInput = document.getElementById('inputfile');
        //检测是否选择文件
    if (!fileInput.value) {
        alert('请上传文件');
        return　false;
    }
    //获取文件相关信息
    var file = fileInput.files[0];
    var fileName = file.name;
    var fileSize = file.size;
    var maxSize = 20971520;
    if (fileSize >= maxSize) {
        alert('文件不能大于20M');
        return false;
    }
    return true;
}
