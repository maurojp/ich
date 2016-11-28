$(document).ready(function() {

    $('#table-pagination2').bootstrapTable({data: []});

    $('#table-pagination').bootstrapTable({data: candidatosTotal });

    $('.pagination-info').attr("style","display: none;");

});


$(document).on('click', '.quitar', function() {
    $seleccionados = $('#table-pagination2').bootstrapTable('getSelections');
    if ($seleccionados.length != 0) {
        var nrosCandidato = $.map($seleccionados, function(row) {
            return row.nroCandidato;
        });
        $('#table-pagination').bootstrapTable('filterBy', {});
        $('#table-pagination').bootstrapTable('load', candidatosTotal);
        $('#table-pagination2').bootstrapTable('remove', {
            field: 'nroCandidato',
            values: nrosCandidato
        });
        $('#table-pagination').bootstrapTable('prepend', $seleccionados);
        candidatosTotal = $('#table-pagination').bootstrapTable('getData');

        $('.pagination-info').attr("style","display: none;");

        $nrosCandidato = $.map($('#table-pagination2').bootstrapTable('getData'), function(row) {
            return row.nroCandidato;
        });

        
        $collectionHolder = $('div.candidatos');
            
            // add a delete link to all of the existing tag form li elements
            $collectionHolder.find('div.renglon').each(function() {

                $(this).remove();
            });

        
            $collectionHolder.data('index', $collectionHolder.find(':input').length);
            var index = $collectionHolder.data('index');

           for (var i=0, total = $nrosCandidato.length; i < total; i++) {

              
               newFormLi = $('<div class="row renglon"></div>').append($('<div class="col-md-2"></div>').append(
                       '<input id="form_candidatos_'+i+'" class="form-control" name="form[candidatos]['+i
                       +']" value="'+ $nrosCandidato[i] +'" type="hidden">'));

            // increase the index with one for the next item
            $collectionHolder.data('index', index + 1);
            
            // Display the form in the page in an li, before the "Add a tag" link li
            
            $collectionHolder.append(newFormLi);
           
           
             
           }
    }
    
});


$('.siguiente').on('click', function(e) {
    
    e.preventDefault(); 

    $seleccionados = $('#table-pagination2').bootstrapTable('getData');

    if ($seleccionados.length == 0)

        $("#myModal").modal({                    // wire up the actual modal functionality and show the dialog
        "backdrop"  : "static",
        "keyboard"  : true,
        "show"      : true                    // ensure the modal is shown immediately
        });
    else {

        $nrosCandidato = $.map($seleccionados, function(row) {
            return row.nroCandidato;})

        var data = { nrosCandidato: $nrosCandidato};
        
        $.ajax({
        type: 'post',
        url: url_buscarCandidatosActivos,
        cache: false,
        data: data, 
        success: function(data){
                
            if(data.length != 0){
                $('div.error').remove();
                for($i=0, $total = data.length; $i < $total; $i++) {
                $('.candidatosActivos').append($('<div class="error"></div>').append(
                '<span class="text-danger"><ul><li>'+data[$i].apellido+', '+data[$i].nombre+'</li></ul></span>'));
                }
            
                $("#modalCandidatosActivos").on("show", function() {    // wire up the OK button to dismiss the modal when shown
                    $("#modalCandidatosActivos a.btn").on("click", function(e) {
                        console.log("button pressed");   // just as an example...
                        $("#modalCandidatosActivos").modal('hide');     // dismiss the dialog
                    });
                });
                $("#modalCandidatosActivos").on("hide", function() {    // remove the event listeners when the dialog is dismissed
                    $("#modalCandidatosActivos a.btn").off("click");
                });

                $("#modalCandidatosActivos").on("hidden", function() {  // remove the actual elements from the DOM when fully hidden
                    $("#modalCandidatosActivos").remove();
                });

                $("#modalCandidatosActivos").modal({                    // wire up the actual modal functionality and show the dialog
                "backdrop"  : "static",
                "keyboard"  : true,
                "show"      : true                     // ensure the modal is shown immediately
                });     
            }

            else 
                $('form').submit();
        }})
    }
})




$(document).on('click', '.aceptar', function() {

        $("#myModal").modal('hide');
        $("#modalCandidatosActivos").modal('hide'); // dismiss the dialog
        $("#busquedaModal").modal('hide');
})


   
$("#myModal").on("show", function() { // wire up the OK button to dismiss the modal when shown
        $("#myModal a.btn").on("click", function(e) {
            console.log("button pressed"); // just as an example...
            $("#myModal").modal('hide'); // dismiss the dialog
        });
    });

    $("#myModal").on("hide", function() { // remove the event listeners when the dialog is dismissed
        $("#myModal a.btn").off("click");
    });

    $("#myModal").on("hidden", function() { // remove the actual elements from the DOM when fully hidden
        $("#myModal").remove();

    });




$(document).on('click', '.refresh', function() {
    $('#table-pagination').bootstrapTable('filterBy', {});
    $('#table-pagination').bootstrapTable('load', candidatosTotal);
    $seleccionados = $('#table-pagination2').bootstrapTable('getData');
    if($seleccionados.length != 0)
    {    
    var nrosCandidato = $.map($seleccionados, function(row) {
        return row.nroCandidato;
    });
    $('#table-pagination').bootstrapTable('remove', {
        field: 'nroCandidato',
        values: nrosCandidato
    });
    }
    $('.pagination-info').attr("style","display: none;");
});


$(document).on('click', '.agregar', function() {

    $seleccionados = $('#table-pagination').bootstrapTable('getSelections');

    if ($seleccionados.length != 0) { 
        $nrosCandidato = $.map($seleccionados, function(row) {
            return row.nroCandidato;
        });

        $('#table-pagination').bootstrapTable('filterBy', {});
        $('#table-pagination').bootstrapTable('load', candidatosTotal);
        $('#table-pagination').bootstrapTable('remove', {
            field: 'nroCandidato',
            values: $nrosCandidato
        });
        $('.pagination-info').attr("style","display: none;");
        candidatosTotal = $('#table-pagination').bootstrapTable('getData');
        
        $('#table-pagination2').bootstrapTable('prepend', $seleccionados);
        $('.pagination-info').attr("style","display: none;");
        $nrosCandidato = $.map($('#table-pagination2').bootstrapTable('getData'), function(row) {
            return row.nroCandidato;
        });   
       
        $collectionHolder = $('div.candidatos');
            
            // add a delete link to all of the existing tag form li elements
            $collectionHolder.find('div.renglon').each(function() {

                $(this).remove();
            });

        
            $collectionHolder.data('index', $collectionHolder.find(':input').length);
            var index = $collectionHolder.data('index');

           for (var i=0, total = $nrosCandidato.length; i < total; i++) {

              
               newFormLi = $('<div class="row renglon"></div>').append($('<div class="col-md-2"></div>').append(
                       '<input id="form_candidatos_'+i+'" class="form-control" name="form[candidatos]['+i+']" value="'+ $nrosCandidato[i] 
                       +'"  type="hidden">'));

            // increase the index with one for the next item
            $collectionHolder.data('index', index + 1);
            
            // Display the form in the page in an li, before the "Add a tag" link li
            
            $collectionHolder.append(newFormLi);
         
           }

    }
       });


    $("#busquedaModal").on("hidden", function() { // remove the actual elements from the DOM when fully hidden
        $("#busquedaModal").remove();

    });


    $(document).on('click', '.buscar', function() {
        $apellido = $('#apellido').val();
        $nombre = $('#nombre').val();
        $nroCandidato = $('#nroCandidato').val();

     if ($apellido.length == 0 && $nombre.length == 0 && $nroCandidato.length == 0)
        $("#busquedaModal").modal({   
            "backdrop"  : "static",
            "keyboard"  : true,
            "show"      : true                   
        })
    else{

        var data = {
        apellido: $apellido,
        nombre: $nombre,
        nroCandidato: $nroCandidato,
        };    
        $.ajax({
         type: 'post',
         url: url_buscarCandidatosEvaluar,
         data: data,
         success: function(data) {
            candidatosTotal = data[0];
            $('#table-pagination').bootstrapTable('load', data[1]); 

        }
    })}

 $('.pagination-info').attr("style","display: none;");
});