$(document).ready(function() {

    var hrefOriginal;

    $('#table-pagination').bootstrapTable({data: puestos});

    $opt= document.getElementById('sig');
    if (null != $opt){
        $opt.id = '#sinSeleccion'; 
        hrefOriginal = $opt.href; 
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
                $opt.href = hrefOriginal.replace('0',$element.idPuesto);  
            }
            else  
            {
                $opt= document.getElementById('sig');
                $opt.href = hrefOriginal.replace('0',$element.idPuesto);
            }
        }
    );  

    
    $(document).on('click', '.aceptar', function() {
                
    $(function() {
            $("#myModal").modal('hide'); // dismiss the dialog
        });
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

})

function mostrarAdvertencia() {

        $("#myModal").modal({                    // wire up the actual modal functionality and show the dialog
          "backdrop"  : "static",
          "keyboard"  : true,
          "show"      : true                    // ensure the modal is shown immediately
        });
}