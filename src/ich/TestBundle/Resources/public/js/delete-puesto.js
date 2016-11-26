var id, row; 

$(document).ready(function() {


    $('.btn-delete').click(function(e) {
        e.preventDefault();

        row = $(this).parents('tr');

        id = row.data('id');

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