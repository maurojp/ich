$(document).ready(function(){

   $('form').bind('submit', function () {
    $(this).find(':input').prop('disabled', false);
      });

    if(preguntasNoRespondidas.length > 0) {

        $("#alert")[0].play();
        
        $("#myModal").modal({
            "backdrop" : "static",
            "keyboard" : true,
            "show" : true,

        })
   
        $("#myModal").on("hide", function() { 
            $("#myModal a.btn").off("click");
        })

        $("#myModal").on("hidden", function() { 
            $("#myModal").remove();
        })
    
        $(document).on('click', '.aceptar', function() {

            $("#myModal").modal('hide');

        })

    }


    $(document).on('click', '.salir', function() {

        $("#salirCuestionarioModal").modal({
        "backdrop" : "static",
        "keyboard" : true,
        "show" : true,
        })
   
        $("#salirCuestionarioModal").on("hide", function() { 
            $("#salirCuestionarioModal a.btn").off("click");
        })

        $("#salirCuestionarioModal").on("hidden", function() { 
            $("#salirCuestionarioModal").remove();
        })


        })
    

     $(document).on('click', '.no', function() {

            $("#salirCuestionarioModal").modal('hide');

        })
})