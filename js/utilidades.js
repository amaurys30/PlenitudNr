//eliminar mensajes despues de 10 segundos
// Esperar a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", function() {
    // Seleccionar el elemento del mensaje
    var mensaje = document.getElementById("mensaje-exito");
    if (mensaje) {
        // Ocultar el mensaje después de 10 segundos (10000 milisegundos)
        setTimeout(function() {
            mensaje.style.display = "none";
        }, 10000);
    }
});