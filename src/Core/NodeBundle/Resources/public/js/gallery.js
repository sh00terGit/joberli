$('#form_upload_file').ajaxForm({
    dataType: 'json',
    beforeSubmit:function () {
        $('#form_upload_file').find('button').attr('disabled', 'disabled');
        $('#form_upload_file').find('input').attr('disabled', 'disabled');
        $('#form_upload_file').find('.loader-ico').html('<img width="20" src="/bundles/core/img/progress_ico.GIF" alt=""/>');

    },
    success: function (data) {
        $('#form_upload_file').find('button').removeAttr('disabled');
        $('#form_upload_file').find('.loader-ico').html('');
        $('#form_upload_file').find('input').removeAttr('disabled');
        $('#form_upload_file').find('input[type=file]').val('');

        if (data.path) {
            $('#img-success').prepend('<div class="col-lg-4" id="AdPh' + data.id + '">' +
            '<div class="block-thumbnail" style="min-width: 147px; max-height: 112px; overflow: hidden;">' +
            '<div style="position: absolute;top: 0;left: -15px;z-index: 1;" class="col-lg-16">' +
            '<div class="btn-group">' +
            '<a class="btn btn-danger btn-xs" onclick="delAdImage(' + data.id + ')"><i class="glyphicon glyphicon-trash"></i></a>' +
            '<a class="btn btn-success btn-xs" onclick="CKEDITOR.instances[\'core_nodebundle_node_main\'].insertHtml(\'<img src=\\\'' + data.path + '\\\'/>\');" ><i class="glyphicon glyphicon-plus"></i></a>' +
            '</div>' +
            '</div>' +
            '<div class="col-lg-16">' +
            '<img class="img-responsive" style="min-height: 112px;" src="' + data.path + '"/>' +
            '</div>'+
            '</div>'+
            '</div>');}
    }
});

function delAdImage(id) {
    $.ajax({
        url: '/node/delete_photo/' + id,
        dataType: 'json',
        type:'post',
        beforeSend: function () {
            $('#form_upload_file .loader-ico').html('<img width="20" src="/bundles/core/img/progress_ico.GIF" alt=""/>');
        },
        success: function (data){
            $('#AdPh' + id + '').hide();
            $('#inp_ph'+id).remove();
            $('#form_upload_file .loader-ico').html('');

        }
    });
}