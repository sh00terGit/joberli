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
            $('#img-success').prepend('<div class="col-xs-3" id="AdPh' + data.id + '">' +
            '<div class="block-thumbnail" style="min-width: 147px; max-height: 112px; overflow: hidden;">' +
            '<a onclick="delAdImage(' + data.id + ')"><i class="glyphicon glyphicon-trash"></i></a>' +
            '<img style="max-width: 160px; min-height: 112px;" src="' + data.path + '"/></div>' +
            '</div>');
            $('#photos-input').prepend('<input id="inp_ph' + data.id + '" type="hidden" name="photos[' + data.id + ']" value="' + data.id + '" >');
        }
    }
});


function loadChildCategory() {
    var category_id = $('select[name=category]').val();

    if (!category_id || category_id == '0') {
        $('#child_cat_select').html('');
        return null;
    }

    $.ajax({
        url: '/ad/load_category',
        dataType: 'json',
        type:'post',
        beforeSend: function () {
            $('#child_cat_select').html('<img width="20" src="/bundles/core/img/progress_ico.GIF" alt=""/>');
        },
        data: {parentCategory: category_id},
        success: function (data){
            $('#child_cat_select').html(data.category_form);
        }
    });
}



function delAdImage(id) {
    $.ajax({
        url: '/ad/delete_photo/' + id,
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
