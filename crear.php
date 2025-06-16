<?php
include "../../includes/conexion.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_nomina = trim($_POST["id_nomina"]);
    $id_empleado = $_POST["id_empleado"];
    $fecha_pago = $_POST["fecha_pago"];
    $salario_base = floatval($_POST["salario_base"]);
    $bonos = floatval($_POST["bonos"]);
    $descuentos = floatval($_POST["descuentos"]);
    $salario_neto = $salario_base + $bonos - $descuentos;

    // Validar ID único
    $stmt_check = $conn->prepare("SELECT COUNT(*) AS total FROM nomina WHERE id_nomina = ?");
    $stmt_check->bind_param("i", $id_nomina);
    $stmt_check->execute();
    $exists = $stmt_check->get_result()->fetch_assoc()["total"];
    $stmt_check->close();

    if ($exists > 0) {
        $error_message = "El ID de nómina ya existe.";
    } else {
        $sql = "INSERT INTO nomina (id_nomina, id_empleado, fecha_pago, salario_base, bonos, descuentos, salario_neto)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisdddd", $id_nomina, $id_empleado, $fecha_pago, $salario_base, $bonos, $descuentos, $salario_neto);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php?success=1");
            exit;
        } else {
            $error_message = "Error al registrar la nómina.";
        }
    }
}

// Obtener lista de empleados
$empleados = $conn->query("SELECT id_empleado, nombre_empleado, apellido_paterno, apellido_materno FROM empleados ORDER BY nombre_empleado ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Nómina</title>
    <link rel="stylesheet" href="../../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../styles/all.min.css">
    <link rel="stylesheet" href="../../styles/styles.css">
</head>
<body>
    <header>
        <h1><a href="../../index.php" class="header-link">CharlOs Streetwear</a></h1>
    </header>

    <div class="container mt-4 content-container">
        <h2 class="text-center mb-4">Registrar Nómina</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="crear.php" method="POST">
            <div class="mb-3">
                <label for="id_nomina" class="form-label">ID de Nómina:</label>
                <input type="number" name="id_nomina" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="id_empleado" class="form-label">Empleado:</label>
                <select name="id_empleado" class="form-control" required>
                    <?php while ($e = $empleados->fetch_assoc()): ?>
                        <option value="<?php echo $e['id_empleado']; ?>">
                            <?php echo $e['nombre_empleado'] . " " . $e['apellido_paterno'] . " " . $e['apellido_materno']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha_pago" class="form-label">Fecha de Pago:</label>
                <input type="date" name="fecha_pago" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="salario_base" class="form-label">Salario Base:</label>
                <input type="number" step="0.01" name="salario_base" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="bonos" class="form-label">Bonos:</label>
                <input type="number" step="0.01" name="bonos" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="descuentos" class="form-label">Descuentos:</label>
                <input type="number" step="0.01" name="descuentos" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
