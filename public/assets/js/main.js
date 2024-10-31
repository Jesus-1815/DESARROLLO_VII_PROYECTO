// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todos los botones de eliminar
    var deleteButtons = document.querySelectorAll('.btn-delete');
    
    // Añadir un event listener a cada botón de eliminar
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            // Prevenir el comportamiento predeterminado del enlace
            e.preventDefault();
            
            // Mostrar un cuadro de diálogo de confirmación
            if (confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
                // Si el usuario confirma, redirigir a la URL de eliminación
                window.location.href = this.href;
            }
        });
    });

    // Seleccionar todos los botones de alternar
    var toggleButtons = document.querySelectorAll('.btn-toggle');
    
    // Añadir un event listener a cada botón de alternar
    toggleButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            // Prevenir el comportamiento predeterminado del enlace
            e.preventDefault();
            
            // Enviar una solicitud AJAX para alternar el estado de la tarea
            fetch(this.href)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Si la operación fue exitosa, alternar la clase 'completed' en el elemento li padre
                        this.closest('li').classList.toggle('completed');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
});