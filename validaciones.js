document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    if (form) {
        // Validación HTML5 personalizada
        form.addEventListener("submit", function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add("was-validated");
        });

        // Habilitar/deshabilitar el botón hasta detectar cambios
        const submitBtn = document.getElementById("submitBtn");
        if (submitBtn) {
            // Guardar los valores originales de los campos
            const originalValues = {};
            form.querySelectorAll("input, textarea, select").forEach(input => {
                if (input.name) {
                    originalValues[input.name] = input.value.trim();
                }
            });

            // Verificar cambios en los campos
            function checkForChanges() {
                let hasChanged = false;
                form.querySelectorAll("input, textarea, select").forEach(input => {
                    if (input.name && originalValues[input.name] !== input.value.trim()) {
                        hasChanged = true;
                    }
                });
                submitBtn.disabled = !hasChanged;
            }

            // Monitorear eventos de entrada y cambio
            form.querySelectorAll("input, textarea, select").forEach(input => {
                input.addEventListener("input", checkForChanges);
                input.addEventListener("change", checkForChanges);
            });

            // Estado inicial del botón
            checkForChanges();
        }
    }
});
