

$(document).ready(function () {
    $('.second-menu a').on('click', function (event) {
        event.preventDefault();
        var table = $(this).data('database');
        $.ajax({
            url: '../api/adatbazis.php',
            method: 'POST',
            data: {table: table, action: 'selectall'},
            success: function (data) {
                $('#database-list').html(data);
            },
            error: function () {
                $('#database-list').html('<p>Hiba történt az adatok betöltése közben.</p>');
            }
        });
    });
});