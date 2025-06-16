<?php
include "../includes/conexion.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_puesto = $_GET['id'];

    // Verificar si hay empleados asociados a este puesto
    $sql_verificar = "SELECT COUNT(*) as total FROM empleados WHERE id_puesto = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("i", $id_puesto);
    $stmt_verificar->execute();
    $resultado = $stmt_verificar->get_result()->fetch_assoc();
    $stmt_verificar->close();

    if ($resultado['total'] > 0) {
        header("Location: index.php?error=relation");
        exit;
    }

    // Proceder a eliminar si no hay empleados
    $sql = "DELETE FROM puestos WHERE id_puesto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_puesto);

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
