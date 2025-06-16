<?php
$servername = "localhost"; 
$username = "root"; 
$password = "abundio171201"; 
$database = "gestion_empleados_admin"; 

// Crear conexión con MySQL usando 'mysqli' con manejo de excepciones
try {
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        throw new Exception("Error en la conexión: " . $conn->connect_error);
    }
} catch (Exception $e) {
    // Log del error detallado
    error_log("Error de conexión: " . $e->getMessage(), 3, 'path/a/tu/logs/error.log');
    die("Hubo un problema al conectar con la base de datos. Por favor, intente más tarde.");
}

// Si usas consultas, es recomendable habilitar el conjunto de resultados y los errores del cliente.
$conn->set_charset("utf8mb4");

?>
