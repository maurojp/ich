$(document).ready(function() {


    $('.buscar').click(function(e) {
        e.preventDefault();

        $codigo = $('#form_codigo').val();
        $nombre = $('#form_nombre').val();
        $empresa = $('#form_empresa').val();

        if ($codigo.length == 0 && $nombre.length == 0 && $empresa.length == 0)
        $("#busquedaModal").modal({   
            "backdrop"  : "static",
            "keyboard"  : true,
            "show"      : true                   
        })

        else
            $('form').submit();


    })

    $("#busquedaModal").on("hidden", function() {  
        $("#busquedaModal").remove();
    });


    $('.aceptar').click(function(){
        $("#busquedaModal").modal('hide');

        })

})