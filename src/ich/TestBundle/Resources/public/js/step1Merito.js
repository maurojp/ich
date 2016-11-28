var href_siguiente, href_buscar;

$(document).ready(function(){

    href_buscar = (document.getElementById('form')).action;

    $(document).on('click', '.refresh', function () {

        $.ajax({
            type: 'get',
            url: href_refresh,
            success: function(data) {

                $('#table-pagination').bootstrapTable('load', data);    

            }
        })
    })


    $('.buscar').on('click', function(e) {

        e.preventDefault(); 

        if ($("#form_puesto").val().length == 0 && $("#form_empresa").val().length == 0 && $("#form_codigo").val().length == 0)
            $("#busquedaModal").modal({                    // wire up the actual modal functionality and show the dialog
                "backdrop"  : "static",
                "keyboard"  : true,
            "show"      : true                    // ensure the modal is shown immediately
        });

            else{

                if($("#form_puesto").val().length == 0)
                $puesto = 0;
                else
                $puesto = $("#form_puesto").val();  

                if($puesto != 0 || $("#form_empresa").val().length == 0)
                $empresa = 0;
                else
                $empresa = $("#form_empresa").val();    

                if($puesto != 0 || $("#form_codigo").val().length == 0)
                $codigo = null;
                else
                $codigo = $("#form_codigo").val();  

                var data = {
                    codigo: $codigo,
                    puesto: $puesto,
                    empresa: $empresa,
                };

                $.ajax({
                    type: 'post',
                    url: href_buscar,
                    data: data,
                    success: function(data) {

                        $('#table-pagination').bootstrapTable('load', data);    

                    }
                })
            }
        })

    $('#table-pagination').bootstrapTable({data: puestos});

    $opt= document.getElementById('sig');
    if (null != $opt){
        $opt.id = '#sinSeleccion'; 
        href_siguiente = $opt.href; 
        $opt.href = '#'; 
        $opt.setAttribute('onclick', 'mostrarAdvertencia();');
    }


$('#table-pagination').on('uncheck.bs.table', function(row, $element) { 

    $opt= document.getElementById('sig');
    if (null != $opt)
    {   
        $opt.setAttribute('onclick', 'mostrarAdvertencia();');
        $opt.id = '#sinSeleccion';
        $opt.href = '#';  
    } 
})



$('#table-pagination').on('check.bs.table', function(row, $element) {

    $opt= document.getElementById('#sinSeleccion');

    if (null != $opt) 
    {   
        $opt.setAttribute('onclick', '');
        $opt.id = 'sig';
        $opt.href = href_siguiente.replace('0',$element.idPuesto);  
    }
    else  
    {
        $opt= document.getElementById('sig');
        $opt.href = href_siguiente.replace('0',$element.idPuesto);
    }
}
);  


$(document).on('click', '.aceptarModal', function() {

    $("#busquedaModal").modal('hide');
    $("#myModal").modal('hide');
})

$("#busquedaModal").on("hidden", function() { 
    $("#busquedaModal").remove();
});


$("#myModal").on("hidden", function() { 
    $("#myModal").remove();

});

})

function mostrarAdvertencia() {

    $("#myModal").modal({                    
        "backdrop"  : "static",
        "keyboard"  : true,
        "show"      : true                   
    });
}