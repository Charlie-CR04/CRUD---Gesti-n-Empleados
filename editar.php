<?php
include "../../includes/conexion.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=notfound");
    exit;
}

$id = $_GET['id'];
$error_message = "";

// Obtener los datos actuales de la nómina
$sql = "SELECT * FROM nomina WHERE id_nomina = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$nomina = $result->fetch_assoc();
$stmt->close();

if (!$nomina) {
    header("Location: index.php?error=notfound");
    exit;
}

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_empleado = $_POST["id_empleado"];
    $fecha_pago = $_POST["fecha_pago"];
    $salario_base = floatval($_POST["salario_base"]);
    $bonos = floatval($_POST["bonos"]);
    $descuentos = floatval($_POST["descuentos"]);
    $salario_neto = $salario_base + $bonos - $descuentos;

    $sql_update = "UPDATE nomina SET id_empleado=?, fecha_pago=?, salario_base=?, bonos=?, descuentos=?, salario_neto=? WHERE id_nomina=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("issdddi", $id_empleado, $fecha_pago, $salario_base, $bonos, $descuentos, $salario_neto, $id);

    if ($stmt_update->execute()) {
        $stmt_update->close();
        header("Location: index.php?success=2");
        exit;
    } else {
        $error_message = "Error al actualizar la nómina.";
    }
}

// Obtener empleados para el select
$empleados = $conn->query("SELECT id_empleado, nombre_empleado, apellido_paterno, apellido_materno FROM empleados ORDER BY nombre_empleado ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Nómina</title>
    <link rel="stylesheet" href="../../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../styles/all.min.css">
    <link rel="stylesheet" href="../../styles/styles.css">
</head>
<body>
    <header>
        <h1><a href="../../index.php" class="header-link">CharlOs Streetwear</a></h1>
    </header>

    <div class="container mt-4 content-container">
        <h2 class="text-center mb-4">Editar Nómina</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="editar.php?id=<?php echo $id; ?>" method="POST">
            <div class="mb-3">
                <label class="form-label">Empleado:</label>
                <select name="id_empleado" class="form-control" required>
                    <?php while ($e = $empleados->fetch_assoc()): ?>
                        <option value="<?php echo $e['id_empleado']; ?>" <?php if ($nomina['id_empleado'] == $e['id_empleado']) echo 'selected'; ?>>
                            <?php echo $e['nombre_empleado'] . " " . $e['apellido_paterno'] . " " . $e['apellido_materno']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha de Pago:</label>
                <input type="date" name="fecha_pago" class="form-control" value="<?php echo $nomina['fecha_pago']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Salario Base:</label>
                <input type="number" step="0.01" name="salario_base" class="form-control" value="<?php echo $nomina['salario_base']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Bonos:</label>
                <input type="number" step="0.01" name="bonos" class="form-control" value="<?php echo $nomina['bonos']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descuentos:</label>
                <input type="number" step="0.01" name="descuentos" class="form-control" value="<?php echo $nomina['descuentos']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
