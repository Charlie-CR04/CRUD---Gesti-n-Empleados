<?php
include "../includes/conexion.php";

$error_message = "";

$empleados = $conn->query("SELECT id_empleado, nombre_empleado, apellido_paterno FROM empleados ORDER BY nombre_empleado ASC");
$horarios = $conn->query("SELECT h.id_horario, e.nombre_empleado, e.apellido_paterno, h.hora_entrada, h.hora_salida 
                          FROM horarios h
                          JOIN empleados e ON h.id_empleado = e.id_empleado
                          ORDER BY h.hora_entrada ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_asistencia = $_POST['id_asistencia'];
    $id_empleado = $_POST['id_empleado'];
    $id_horario = $_POST['id_horario'];
    $fecha = $_POST['fecha'];
    $hora_entrada = $_POST['hora_entrada'];
    $hora_salida = $_POST['hora_salida'];
    $estado = $_POST['estado'];

    $sql = "INSERT INTO asistencias (id_asistencia, id_empleado, id_horario, fecha, hora_entrada, hora_salida, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiissss", $id_asistencia, $id_empleado, $id_horario, $fecha, $hora_entrada, $hora_salida, $estado);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php?success=1");
        exit;
    } else {
        $error_message = "Error al registrar la asistencia.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Asistencia</title>
    <<link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <header>
        <h1>
            <a href="../index.php" class="header-link">CharlOs Streetwear</a>
        </h1>
    </header>

    <div class="container mt-4 content-container">
        <h2 class="text-center mb-4">Registrar Asistencia</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="crear.php" method="POST">
            <div class="mb-3">
                <label for="id_asistencia" class="form-label">ID Asistencia:</label>
                <input type="number" id="id_asistencia" name="id_asistencia" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="id_empleado" class="form-label">Empleado:</label>
                <select id="id_empleado" name="id_empleado" class="form-control" required>
                    <?php while ($row = $empleados->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_empleado']; ?>">
                            <?php echo htmlspecialchars($row['nombre_empleado'] . " " . $row['apellido_paterno']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_horario" class="form-label">Horario:</label>
                <select id="id_horario" name="id_horario" class="form-control" required>
                    <?php while ($row = $horarios->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_horario']; ?>">
                            <?php echo htmlspecialchars($row['nombre_empleado'] . " " . $row['apellido_paterno']) .
                                   " - Entrada: " . $row['hora_entrada'] . " / Salida: " . $row['hora_salida']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha:</label>
                <input type="date" id="fecha" name="fecha" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="hora_entrada" class="form-label">Hora Entrada:</label>
                <input type="time" id="hora_entrada" name="hora_entrada" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="hora_salida" class="form-label">Hora Salida:</label>
                <input type="time" id="hora_salida" name="hora_salida" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select id="estado" name="estado" class="form-control" required>
                    <option value="Presente">Presente</option>
                    <option value="Tarde">Tarde</option>
                    <option value="Ausente">Ausente</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
