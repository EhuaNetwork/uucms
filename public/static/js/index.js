
layui.use('upload', function(){
  var $ = layui.jquery
  ,upload = layui.upload;
  
  //普通图片上传
  var uploadInst = upload.render({
    elem: '#face_pic'
    ,url: '/user/ajax_upload_img.php?dopost=faceSave'
    ,before: function(obj){
        layer_loading('正在处理');
        //预读本地文件示例，不支持ie8
        obj.preview(function(index, file, result){
          $('#user_face').attr('data-src', result); //图片链接（base64）
        });
    }
    ,done: function(res, obj){
      layer.closeAll();
      //上传成功
      if(res.code == 1){
        $('#user_face').attr('src', $('#user_face').attr('data-src'));
        $('#user_face_top').attr('src', $('#user_face').attr('data-src'));
        layer.msg(res.msg, {icon: 1, time: 1500});
      } else {
        $('#user_face').attr('src', $('#oldface').val());
        return layer.msg(res.msg, {icon: 2, time: 2000});
      }
    }
    ,error: function(){
      layer.closeAll();
      //演示失败状态，并实现重传
      $('#user_face').attr('src', $('#oldface').val());
      var confirm1 = layer.confirm('上传失败，是否重试？', {
              title: false
              ,closeBtn: false
              ,btn: ['是','否'] //按钮

          }, function(){
              layer.close(confirm1);
              uploadInst.upload();
          }
      );
    }
  });
});

function update_msg()
{
    layer_loading('正在处理');
    var user_msg = $('#user_msg').val();
    var url = eyou_basefile + "?m=user&c=Users&a=update_msg";
    $.ajax({
        url: url,//"/user/index_do.php",
        type: 'POST',
        dataType: 'JSON',
        data: {value:user_msg},
        success: function(res){
            layer.closeAll();
            $('#modal_close').click();
            if (res.code == 1) {
                $('#moodcontent').html(res.html);
                layer.msg(res.msg, {icon: 1, time:1000});
            } else {
                layer.alert(res.msg);
                return false;
            }
        },
        error: function(e){
            layer.closeAll();
            layer.alert('网络失败，请刷新页面后重试');
            return false;
        }
    });
}