var id, row; 

$(document).ready(function() {


    $('.btn-delete').click(function(e) {
        e.preventDefault();

        row = $(this).parents('tr');

        id = row.data('id');

        nombrePuesto = row.find('td').eq(1).text();

        $('div.alertModelo').remove();
        $('.mensaje').append($('<div class="alertModelo"></div>').append(
            '<h4>Los datos del Puesto '+ nombrePuesto +' serán eliminados del sistema.</h4><p><h4>¿Desea continuar?</h4></p>'));

        $("#modalEliminar").modal({                    
            "backdrop"  : "static",
            "keyboard"  : true,
            "show"      : true                     
        });  


    })

    $("#modalEliminar").on("hidden", function() {  
        $("#modalEliminar").remove();
    });

    $('.noEliminar').click(function(){

        $("#modalEliminar").modal('hide');
    })

    $('.eliminar').click(function(){

        $("#modalEliminar").modal('hide');

        $('#delete-progress').removeClass('hidden');

        $deleteButton = document.getElementById('deleteButton');
        if (null != $deleteButton){


            $.ajax({
                type: 'post',
                url: $deleteButton.href,
                data: {id: id},
                cache: false,
                success: function(result){

                    $('#delete-progress').addClass('hidden');

                    row.fadeOut();
                    $('#message').removeClass('hidden');

                    $('#puesto-message').text(result.message);      

                },
                error: function(result) {

                    $('#messageDanger').removeClass('hidden');

                    $('#puestoMessageDanger').text(result.responseJSON);


                }})}
        }

        )

})