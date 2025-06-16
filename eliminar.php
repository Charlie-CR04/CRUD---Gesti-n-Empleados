<?php
include "../includes/conexion.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=notfound");
    exit;
}

$id = $_GET['id'];

// Verificar si el empleado tiene registros relacionados (por ejemplo en nomina, horarios o asistencias)
$checkRelations = [
    "nomina" => "id_empleado",
    "horarios" => "id_empleado",
    "asistencias" => "id_empleado"
];

$hasRelations = false;

foreach ($checkRelations as $table => $column) {
    $sql_check = "SELECT COUNT(*) as total FROM $table WHERE $column = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id);
    $stmt_check->execute();
    $result = $stmt_check->get_result()->fetch_assoc();
    $stmt_check->close();

    if ($result['total'] > 0) {
        $hasRelations = true;
        break;
    }
}

if ($hasRelations) {
    header("Location: index.php?error=relation");
    exit;
}

// Si no hay relaciones, eliminar al empleado
$sql_delete = "DELETE FROM empleados WHERE id_empleado = ?";
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
