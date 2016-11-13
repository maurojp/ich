$(document).ready(function(){

// Devuelve los parametros pasados por URI

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
    
    var loop = getUrlParameter('loop');
    
    if (loop == 'true') {
        
        bootbox.confirm({
        message: "Desea agregar un nuevo puesto?",
        buttons: {
            confirm: {
                label: 'Si',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
        if (result == false)
        {
             
            $(location).attr('href',indexroute);
            //alert('Redirijo a Index');
        }
        }
    });
            
    }
});
