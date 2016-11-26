$(document).ready(function() {
    $('.btn-delete').click(function(e) {
        e.preventDefault();

        var row = $(this).parents('tr');

        var data = { id : row.data('id') };

        bootbox.confirm(message, function(res) {
            if(res == true)
            {
                $('#delete-progress').removeClass('hidden');

                $.post(url, data, function(result){

                    $('#delete-progress').addClass('hidden');

                    row.fadeOut();
                    $('#message').removeClass('hidden');

                    $('#pregunta-message').text(result.message);


                }).fail(function() {
                    alert('ERROR');
                    row.show();
                });
            }
        });
    });
});