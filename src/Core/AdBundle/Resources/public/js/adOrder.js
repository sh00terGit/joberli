function adOrderRemove(id) {
    var route = Routing.generate('ad_order_remove', { id: id }) + '?action=remove';
    $.ajax({
        url:route,
        dataType:'json',
        type: 'post',
        success: function (data) {
            if (data.success) {
                $('.modal-body').html(data.success);
                $('tr#adOrder' + id).slideUp(200);
            }
        }
    });
}


function adOrderAdopt(id) {
    var route = Routing.generate('ad_order_adopt', { id: id }) + '?action=adopt';
    $.ajax({
        url:route,
        dataType:'json',
        type: 'post',
        success: function (data) {
            if (data.success) {
                $('.modal-body').html(data.success);
                $('#ajax-modal').modal('hide');
                $('#adOrder' + id + ' td.actions').html('');
                $('#adOrder' + id + ' td.status').html(data.status);

            }
        }
    });
}