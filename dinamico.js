document.addEventListener("DOMContentLoaded", function() {
    // Ocultar mensaje de éxito dinámico con desvanecimiento
    var successMessage = document.getElementById("successMessage");
    if (successMessage) {
        setTimeout(function() {
            successMessage.style.transition = "opacity 0.5s";
            successMessage.style.opacity = "0";
            setTimeout(function() {
                successMessage.style.display = "none";
            }, 500); // Tiempo para que termine la transición
        }, 3000); // 3 segundos
    }

    // Ocultar mensaje de error dinámico con desvanecimiento
    var errorMessage = document.getElementById("errorMessage");
    if (errorMessage) {
        setTimeout(function() {
            errorMessage.style.transition = "opacity 0.5s";
            errorMessage.style.opacity = "0";
            setTimeout(function() {
                errorMessage.style.display = "none";
            }, 500);
        }, 3000);
    }

    // Confirmación para eliminar
    var deleteButtons = document.querySelectorAll(".delete-button");
    deleteButtons.forEach(function(button) {
        button.addEventListener("click", function(event) {
            var confirmDelete = confirm("¿Estás seguro de que deseas eliminar este registro?");
            if (!confirmDelete) {
                event.preventDefault(); // Cancela la acción si el usuario no confirma
            }
        });
    });
});
