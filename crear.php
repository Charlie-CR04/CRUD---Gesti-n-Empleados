<?php
include "../../includes/conexion.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pago = $_POST['id_pago'];
    $id_nomina = $_POST['id_nomina'];
    $fecha_pago = $_POST['fecha_pago'];
    $monto = $_POST['monto'];
    $metodo_pago = $_POST['metodo_pago'];

    // Verificar si el ID ya existe
    $check_sql = "SELECT COUNT(*) AS total FROM pagos WHERE id_pago = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("i", $id_pago);
    $stmt_check->execute();
    $resultado = $stmt_check->get_result()->fetch_assoc();
    $stmt_check->close();

    if ($resultado['total'] > 0) {
        $error_message = "El ID del pago ya existe. Por favor, usa otro.";
    } else {
        // Insertar pago
        $sql = "INSERT INTO pagos (id_pago, id_nomina, fecha_pago, monto, metodo_pago) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $id_pago, $id_nomina, $fecha_pago, $monto, $metodo_pago);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php?success=1");
            exit;
        } else {
            $error_message = "Error al registrar el pago.";
        }

        $stmt->close();
    }
}

// Obtener nóminas
$sql_nominas = "SELECT n.id_nomina, n.fecha_pago, e.nombre_empleado, e.apellido_paterno, e.apellido_materno
                FROM nomina n
                INNER JOIN empleados e ON n.id_empleado = e.id_empleado
                ORDER BY n.fecha_pago DESC";
$result_nominas = $conn->query($sql_nominas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Pago</title>
    <link rel="stylesheet" href="../../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../styles/all.min.css">
    <link rel="stylesheet" href="../../styles/styles.css">
</head>
<body>
    <header>
        <h1><a href="../../index.php" class="header-link">CharlOs Streetwear</a></h1>
    </header>

    <div class="container mt-4 content-container">
        <h2 class="text-center mb-4">Registrar Nuevo Pago</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="crear.php" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="id_pago" class="form-label">ID del Pago:</label>
                <input type="number" name="id_pago" id="id_pago" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="id_nomina" class="form-label">Seleccionar Nómina:</label>
                <select name="id_nomina" id="id_nomina" class="form-select" required>
                    <option value="">Seleccione una nómina</option>
                    <?php while ($row = $result_nominas->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_nomina']; ?>">
                            <?php echo $row['id_nomina'] . " - " . $row['nombre_empleado'] . " " . $row['apellido_paterno'] . " (" . $row['fecha_pago'] . ")"; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha_pago" class="form-label">Fecha de Pago:</label>
                <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="monto" class="form-label">Monto:</label>
                <input type="number" step="0.01" name="monto" id="monto" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="metodo_pago" class="form-label">Método de Pago:</label>
                <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                    <option value="">Seleccione un método</option>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Transferencia">Transferencia</option>
                    <option value="Cheque">Cheque</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Registrar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="../../scripts/validaciones.js"></script>
</body>
</html>
