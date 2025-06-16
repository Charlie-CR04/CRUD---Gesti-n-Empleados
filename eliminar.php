<?php
include "../../includes/conexion.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=notfound");
    exit;
}

$id = $_GET['id'];

// Verificar si hay pagos vinculados a esta nómina
$sql_check = "SELECT COUNT(*) AS total FROM pagos WHERE id_nomina = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $id);
$stmt_check->execute();
$result = $stmt_check->get_result()->fetch_assoc();
$stmt_check->close();

if ($result['total'] > 0) {
    header("Location: index.php?error=relation");
    exit;
}

// Si no hay pagos, eliminar la nómina
$sql_delete = "DELETE FROM nomina WHERE id_nomina = ?";
$stmt = $conn->prepare($sql_delete);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: index.php?success=3");
    exit;
} else {
    $stmt->close();
    header("Location: index.php?error=deletefail");
    exit;
}
?>
