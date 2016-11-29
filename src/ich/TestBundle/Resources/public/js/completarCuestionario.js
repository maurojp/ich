$(document).ready(function(){

   $('form').bind('submit', function () {
    $(this).find(':input').prop('disabled', false);
      });

    if(preguntasNoRespondidas.length > 0) {

        $("#alert")[0].play();
        
        $("#myModal").modal({ // wire up the actual modal functionality and show the dialog
            "backdrop" : "static",
            "keyboard" : true,
            "show" : true,
        // ensure the modal is shown immediately
        })
   
        $("#myModal").on("hide", function() { // remove the event listeners when the dialog is dismissed
            $("#myModal a.btn").off("click");
        })

        $("#myModal").on("hidden", function() { // remove the actual elements from the DOM when fully hidden
            $("#myModal").remove();
        })
    
        $(document).on('click', '.aceptar', function() {

            $("#myModal").modal('hide');// dismiss the dialog

        })

    }
})