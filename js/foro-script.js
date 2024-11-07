$(document).ready(function() {
    $('.mostrar-respuestas').click(function() {
        var comentarioId = $(this).data('comentario-id');
        var respuestasDiv = $('#respuestas-' + comentarioId);

        // Alternar la visibilidad de las respuestas
        if (respuestasDiv.is(':visible')) {
            respuestasDiv.hide();
            $(this).text('Mostrar respuestas'); // Cambia el texto del botón
        } else {
            respuestasDiv.show();
            $(this).text('Ocultar respuestas'); // Cambia el texto del botón
        }
    });
});
