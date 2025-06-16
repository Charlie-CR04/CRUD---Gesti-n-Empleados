<?php
include "../includes/conexion.php";

$error_message = "";
$asistencia = null;

// Obtener lista de empleados y horarios
$empleados = $conn->query("SELECT id_empleado, nombre_empleado, apellido_paterno FROM empleados ORDER BY nombre_empleado ASC");
$horarios = $conn->query("SELECT h.id_horario, e.nombre_empleado, e.apellido_paterno, h.hora_entrada, h.hora_salida 
                          FROM horarios h
                          JOIN empleados e ON h.id_empleado = e.id_empleado
                          ORDER BY h.hora_entrada ASC");

// Verificar si se recibió el ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_asistencia = $_GET['id'];

    $sql = "SELECT * FROM asistencias WHERE id_asistencia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_asistencia);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $asistencia = $resultado->fetch_assoc();
    $stmt->close();

    if (!$asistencia) {
        $error_message = "Asistencia no encontrada.";
    }
} else {
    $error_message = "ID de asistencia no proporcionado.";
}

// Procesar si se envió formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_asistencia = $_POST['id_asistencia'];
    $id_empleado = $_POST['id_empleado'];
    $id_horario = $_POST['id_horario'];
    $fecha = $_POST['fecha'];
    $hora_entrada = $_POST['hora_entrada'];
    $hora_salida = $_POST['hora_salida'];
    $estado = $_POST['estado'];

    $sql = "UPDATE asistencias SET id_empleado = ?, id_horario = ?, fecha = ?, hora_entrada = ?, hora_salida = ?, estado = ?
            WHERE id_asistencia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissssi", $id_empleado, $id_horario, $fecha, $hora_entrada, $hora_salida, $estado, $id_asistencia);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php?success=2");
        exit;
    } else {
        $error_message = "Error al actualizar la asistencia.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Asistencia</title>
    <<link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <header>
        <h1><a href="../index.php" class="header-link">CharlOs Streetwear</a></h1>
    </header>

    <div class="container mt-4 content-container">
        <h2 class="text-center mb-4">Editar Asistencia</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if ($asistencia): ?>
        <form action="editar.php" method="POST">
            <input type="hidden" name="id_asistencia" value="<?php echo htmlspecialchars($asistencia['id_asistencia']); ?>">

            <div class="mb-3">
                <label for="id_empleado" class="form-label">Empleado:</label>
                <select id="id_empleado" name="id_empleado" class="form-control" required>
                    <?php foreach ($empleados as $row): ?>
                        <option value="<?php echo $row['id_empleado']; ?>"
                            <?php echo ($asistencia['id_empleado'] == $row['id_empleado']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['nombre_empleado'] . " " . $row['apellido_paterno']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_horario" class="form-label">Horario:</label>
                <select id="id_horario" name="id_horario" class="form-control" required>
                    <?php foreach ($horarios as $row): ?>
                        <option value="<?php echo $row['id_horario']; ?>"
                            <?php echo ($asistencia['id_horario'] == $row['id_horario']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['nombre_empleado'] . " " . $row['apellido_paterno']) .
                                   " - Entrada: " . $row['hora_entrada'] . " / Salida: " . $row['hora_salida']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha:</label>
                <input type="date" id="fecha" name="fecha" class="form-control"
                       value="<?php echo htmlspecialchars($asistencia['fecha']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="hora_entrada" class="form-label">Hora Entrada:</label>
                <input type="time" id="hora_entrada" name="hora_entrada" class="form-control"
                       value="<?php echo htmlspecialchars($asistencia['hora_entrada']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="hora_salida" class="form-label">Hora Salida:</label>
                <input type="time" id="hora_salida" name="hora_salida" class="form-control"
                       value="<?php echo htmlspecialchars($asistencia['hora_salida']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select id="estado" name="estado" class="form-control" required>
                    <option value="Presente" <?php echo ($asistencia['estado'] == 'Presente') ? 'selected' : ''; ?>>Presente</option>
                    <option value="Tarde" <?php echo ($asistencia['estado'] == 'Tarde') ? 'selected' : ''; ?>>Tarde</option>
                    <option value="Ausente" <?php echo ($asistencia['estado'] == 'Ausente') ? 'selected' : ''; ?>>Ausente</option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning">Actualizar Asistencia</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
