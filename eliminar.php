<?php
include "../includes/conexion.php";

$error_message = "";

// Verificar si se recibió el ID del horario
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_horario = $_GET['id'];

    // Verificar si el horario existe
    $sql_verificar = "SELECT * FROM horarios WHERE id_horario = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("i", $id_horario);
    $stmt_verificar->execute();
    $resultado = $stmt_verificar->get_result();
    $horario = $resultado->fetch_assoc();
    $stmt_verificar->close();

    if ($horario) {
        // Eliminar el horario
        $sql = "DELETE FROM horarios WHERE id_horario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_horario);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php?success=3"); // Redirigir con mensaje de éxito
            exit;
        } else {
            $error_message = "Error al eliminar el horario.";
        }

        $stmt->close();
    } else {
        header("Location: index.php?error=notfound");
        exit;
    }
} else {
    header("Location: index.php?error=notfound");
    exit;
}
?>
