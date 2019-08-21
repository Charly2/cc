$(window).on('hashchange', function() {
    if (window.location.hash) {
        var page = window.location.hash.replace('#', '');
        if (page == Number.NaN || page <= 0) {
            return false;
        } else {
            getData(page);
        }
    }
});

$(document).ready(function() {
    $(document).on('click', '.pagination a', function (e) {
        getData($(this).attr('href').split('page=')[1]);
        e.preventDefault();
    });
    $(document).on('click', '[pagination-action]', function (e) {
        getData(0);
        e.preventDefault();
    });
});

function getData(page) {
    var data = $("form[pagination-form]").serializeArray();
    var query = $.param(data);
    $.ajax({
        url : '?page=' + page +'&'+query,
        dataType: 'html',
    }).done(function (data) {
        $('#content-data-pagination').html(data);
        location.hash = page;
    }).fail(function () {
        alert('No fue posible cargar la informacion.');
    });
}