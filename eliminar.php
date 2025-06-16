<?php
include "../includes/conexion.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_asistencia = $_GET['id'];

    // Verificar si la asistencia existe
    $sql_verificar = "SELECT id_asistencia FROM asistencias WHERE id_asistencia = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("i", $id_asistencia);
    $stmt_verificar->execute();
    $resultado = $stmt_verificar->get_result();
    $stmt_verificar->close();

    if ($resultado->num_rows > 0) {
        // Eliminar asistencia
        $sql = "DELETE FROM asistencias WHERE id_asistencia = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_asistencia);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php?success=3");
            exit;
        } else {
            $stmt->close();
            header("Location: index.php?error=delete");
            exit;
        }
    } else {
        header("Location: index.php?error=notfound");
        exit;
    }
} else {
    header("Location: index.php?error=notfound");
    exit;
}
?>
