<?php
include "../includes/conexion.php";

$error_message = "";

// Obtener empleados para el selector
$sql_empleados = "SELECT id_empleado, nombre_empleado, apellido_paterno FROM empleados ORDER BY nombre_empleado ASC";
$empleados = $conn->query($sql_empleados);

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_horario = $_POST['id_horario'];
    $id_empleado = $_POST['id_empleado'];
    $hora_entrada = $_POST['hora_entrada'];
    $hora_salida = $_POST['hora_salida'];

    // Validar que el ID no esté repetido
    $verificar = $conn->prepare("SELECT COUNT(*) AS total FROM horarios WHERE id_horario = ?");
    $verificar->bind_param("i", $id_horario);
    $verificar->execute();
    $resultado = $verificar->get_result()->fetch_assoc();
    $verificar->close();

    if ($resultado['total'] > 0) {
        $error_message = "Ya existe un horario con ese ID.";
    } else {
        $sql = "INSERT INTO horarios (id_horario, id_empleado, hora_entrada, hora_salida) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $id_horario, $id_empleado, $hora_entrada, $hora_salida);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php?success=1");
            exit;
        } else {
            $error_message = "Error al asignar horario.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Horario</title>
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<header>
    <h1><a href="../index.php" class="header-link">CharlOs Streetwear</a></h1>
</header>

<div class="container mt-4 content-container">
    <h2 class="text-center mb-4">Asignar Horario a Empleado</h2>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="crear.php" method="POST">
        <div class="mb-3">
            <label for="id_horario" class="form-label">ID del Horario:</label>
            <input type="number" name="id_horario" id="id_horario" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="id_empleado" class="form-label">Empleado:</label>
            <select name="id_empleado" id="id_empleado" class="form-control" required>
                <option value="">Seleccione un empleado</option>
                <?php while ($row = $empleados->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_empleado']; ?>">
                        <?php echo htmlspecialchars($row['nombre_empleado'] . " " . $row['apellido_paterno']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="hora_entrada" class="form-label">Hora de Entrada:</label>
            <input type="time" name="hora_entrada" id="hora_entrada" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="hora_salida" class="form-label">Hora de Salida:</label>
            <input type="time" name="hora_salida" id="hora_salida" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
