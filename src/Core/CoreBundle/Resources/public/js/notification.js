function deleteNotification (id, count) {
    var link = Routing.generate('core_notification_show', {id: id});

    count = $('#notificationsTopCount').text();
    if (count) {
        count = parseInt(count);
    } else {
        count = 0;
    }

    if (count > 0) {
        count--;
    }



    $('.dropdown-notification').click(function(e) {
        e.stopPropagation();
    });

    $.ajax({
        url: link,
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            $('#topNotification' + id).slideUp(200);

            if (count == 0) {
                $('#notificationsTopCount').remove();
            } else {
                $('#notificationsTopCount').text(count);
            }

        }
    })
    return false;
}