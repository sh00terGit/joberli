var Ad = function   () {
    var urlAddOrRemoveFavorite = '/';
    var addOrRemoveFavorite = function () {
        $('a.adLike').click(function() {
            var link = $(this);
            var id = link.attr('data-productid');
            console.log(id);

            if (typeof id !== undefined && id < 1) {
                return;
            }
            $.ajax({
                url: urlAddOrRemoveFavorite,
                type: 'POST',
                dataType: 'json',
                data: {id: id},
                success: function (data) {
                    if (data.action === 'add') {
                        link.attr('data-original-title', 'Удалить из избранного');
                    } else if (data.action === 'remove') {
                        link.attr('data-original-title', 'Добавить в избранное');
                    }
                }, error: function (data) {

                }
            })
        })
    };

    return {

        initAddOrRemoveFavorite: function () {
            addOrRemoveFavorite();
        },
        init: function (links) {
            urlAddOrRemoveFavorite = links.urlAddOrRemoveFavorite;
            this.initAddOrRemoveFavorite();
        }
    };
}();