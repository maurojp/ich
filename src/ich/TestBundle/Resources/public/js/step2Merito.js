$(document).ready(function(){

    if(evaluaciones.length > 0){


        $('#table-pagination').bootstrapTable({data: evaluaciones});

        $('#table-pagination').on('uncheck.bs.table', cargarForm)

        $('#table-pagination').on('check.bs.table', cargarForm)

        $('#table-pagination').on('check-all.bs.table', cargarForm)

        $('#table-pagination').on('uncheck-all.bs.table', cargarForm)

        $('.aceptar').on('click', function(e) {

            e.preventDefault(); 

            $seleccionados = $('#table-pagination').bootstrapTable('getSelections');

            if ($seleccionados.length == 0)
                $("#myModal").modal({                    // wire up the actual modal functionality and show the dialog
                    "backdrop"  : "static",
                    "keyboard"  : true,
                "show"      : true                    // ensure the modal is shown immediately
            });

                else 
                $('form').submit();
            })


            $("#myModal").on("hidden", function() { // remove the actual elements from the DOM when fully hidden
                $("#myModal").remove();

            });

        }
        else{

             $("#myModal2").modal({                    // wire up the actual modal functionality and show the dialog
                "backdrop"  : "static",
                "keyboard"  : true,
                "show"      : true                    // ensure the modal is shown immediately
            });

            $("#myModal2").on("hidden", function() { // remove the actual elements from the DOM when fully hidden
                $("#myModal2").remove();

            });
    }


    $(document).on('click', '.aceptarModal', function() {

            $("#myModal").modal('hide');
            $("#myModal2").modal('hide');
    })

})

function cargarForm() {

    $seleccionados = $('#table-pagination').bootstrapTable('getSelections');

    var idsEvaluaciones = $.map($seleccionados, function(row) {
        return row.id;
    });

    $collectionHolder = $('div.evaluaciones');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('div.renglon').each(function() {

        $(this).remove();
    });


    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    var index = $collectionHolder.data('index');

    for (var i=0, total = idsEvaluaciones.length; i < total; i++) {


        newFormLi = $('<div class="row renglon"></div>').append($('<div class="col-md-2"></div>').append(
            '<input id="form_evaluaciones_'+i+'" class="form-control" name="form[evaluaciones]['+i
                +']" value="'+ idsEvaluaciones[i] +'" type="hidden">'));

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);
        
        // Display the form in the page in an li, before the "Add a tag" link li
        
        $collectionHolder.append(newFormLi);

    }

}