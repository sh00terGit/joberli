function modalAlert(text, func) {
    $.ajax({
        url: '',
        dataType: 'json',
        type: 'post',
        data: {text:text, func:func},
        success: function (data) {
            $().modal('show');
        }
    })
}