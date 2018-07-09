function createDialog (recipient) {
    var $modal = $('#ajax-modal');
    $('body').modalmanager('loading');

    setTimeout(function(){
        $modal.load(Routing.generate('msg_create_dialog', {recipient: recipient}), '', function(){
            $modal.modal();
        });
    }, 1000);
}


$('#formSendMessage').ajaxForm({
    dataType: 'html',
    success: function (data) {
        $('#formSendMessage textarea').val('');
        $('#dialogBodyContainer').append(data);
        var block = document.getElementById("dialogBodyContainer");
        block.scrollTop = block.scrollHeight;
    }
});