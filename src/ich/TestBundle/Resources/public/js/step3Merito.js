$(document).ready(function(){

   $('#table-candidatosAptos').bootstrapTable({data: cuestionariosAptos});

   $(document).on('click', '.aceptar', function() {
    
        $("#myModal").modal('hide');
    })

   $(function() {
    $("#myModal").modal('hide');
            $("#myModal").on("hidden", function() { // remove the actual elements from the DOM when fully hidden
                $("#myModal").remove();
            });
        })

     $("#myModal").on("show", function() { // wire up the OK button to dismiss the modal when shown
        $("#myModal a.btn").on("click", function(e) {
                console.log("button pressed"); // just as an example...
                $("#myModal").modal('hide'); // dismiss the dialog
            });
    });


    $(document).on('click', '.imprimir', function() {

        if(cuestionariosAptos.length == 0){              
        $("#myModal").modal({                    // wire up the actual modal functionality and show the dialog
            "backdrop"  : "static",
            "keyboard"  : true,
            "show"      : true                    // ensure the modal is shown immediately
        });

        }
        else{
            var header = '<div class="row">Informe: Orden de Mérito</div>'+'<div class="row">Puesto: '+nombrePuesto+'</div>'+'<div class="row">Empresa: '+nombreEmpresa+'</div>';
            var title = 'Mérito--'+nombrePuesto+'--Fecha:'+dateTimeInforme;
            printTable(title, header, dateTimeInforme,'#table-candidatosAptos');
        }
    })


    $(function buildTable() {

    $('#table-evaluaciones').bootstrapTable({
      columns: [{
      field: 'evaluacionHeader'
      }],
      data: evaluaciones,
      detailView: true,
      striped: true,
      height: 330,
      showHeader: false,
      onExpandRow: function(index, row, $detail) {
          console.log(row);
          $detail.html('<table></table>').find('table').bootstrapTable({
            columns: [{
                field: 'header'
            }],
            data: row.cuestionarios,
            detailView: true,
            showHeader: false,
            onExpandRow: function(index2, row2, $detail2) {
                console.log(row2);
                try{
                 if(row2.cuestionarios[0]['header'] == "Cuestionarios Aptos"){

                    $detail2.html('<table></table>').find('table').bootstrapTable({
                        columns: [{
                            field: 'header'
                        }],
                        data: row2.cuestionarios,
                        detailView: true,
                        showHeader: false,
                        onExpandRow: function(index3, row3, $detail3) {
                            console.log(row3);
                            $detail3.html('<table></table>').find('table').bootstrapTable({
                                columns: [{
                                    field: 'tipoDocumento',
                                    title: 'Tipo Documento',
                                    sortable: true
                                },
                                {
                                    field: 'documento',
                                    title: 'Documento',
                                    sortable: true
                                },
                                {
                                    field: 'apellido',
                                    title: 'Apellido',
                                    sortable: true
                                },
                                {
                                    field: 'nombre',
                                    title: 'Nombre',
                                    sortable: true
                                },
                                {
                                    field: 'puntajeObtenido',
                                    title: 'Puntaje %',
                                    sortable: true
                                },
                                {
                                    field: 'fechaInicio',
                                    title: 'Fecha Inicio',
                                    sortable: true
                                },
                                {
                                    field: 'fechaFin',
                                    title: 'Fecha Finalización',
                                    sortable: true
                                },
                                {
                                    field: 'cantAccesos',
                                    title: 'Cant. Accesos',
                                    sortable: true
                                }],
                                data: row3.cuestionarios
                            }
                            )}
                        })

                }
                else{

                    $detail2.html('<table></table>').find('table').bootstrapTable({
                        columns: [{
                            field: 'header'
                        }],
                        data: row2.cuestionarios,
                        detailView: true,
                        showHeader: false,
                        onExpandRow: function(index3, row3, $detail3) {
                          console.log(row3);
                          $detail3.html('<table></table>').find('table').bootstrapTable({
                            columns: [{
                                field: 'tipoDocumento',
                                title: 'Tipo Documento',
                                sortable: true
                            },
                            {
                                field: 'documento',
                                title: 'Documento',
                                sortable: true
                            },
                            {
                                field: 'apellido',
                                title: 'Apellido',
                                sortable: true
                            },
                            {
                                field: 'nombre',
                                title: 'Nombre',
                                sortable: true
                            },
                            {
                                field: 'fechaInicio',
                                title: 'Fecha Inicio',
                                sortable: true
                            },
                            {
                                field: 'ultimoIngreso',
                                title: 'Último Ingreso',
                                sortable: true
                            },
                            {
                                field: 'cantAccesos',
                                title: 'Cant. Accesos',
                                sortable: true
                            }],
                            data: row3.cuestionarios
                            }
                        )}
                    })   
                }
            }
            catch(ex){}
        }
    })

    }

    })})
})