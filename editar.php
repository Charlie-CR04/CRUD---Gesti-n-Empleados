<?php
include "../includes/conexion.php";

$error_message = "";
$horario = null;

// Obtener lista de empleados
$sql_empleados = "SELECT id_empleado, nombre_empleado, apellido_paterno FROM empleados ORDER BY nombre_empleado ASC";
$empleados = $conn->query($sql_empleados);

// Obtener el horario a editar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_horario = $_GET['id'];

    $sql = "SELECT * FROM horarios WHERE id_horario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_horario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $horario = $resultado->fetch_assoc();
    $stmt->close();

    if (!$horario) {
        $error_message = "Horario no encontrado.";
    }
} else {
    $error_message = "ID de horario no proporcionado.";
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_horario = $_POST['id_horario'];
    $id_empleado = $_POST['id_empleado'];
    $hora_entrada = $_POST['hora_entrada'];
    $hora_salida = $_POST['hora_salida'];

    $sql = "UPDATE horarios SET id_empleado = ?, hora_entrada = ?, hora_salida = ? WHERE id_horario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $id_empleado, $hora_entrada, $hora_salida, $id_horario);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php?success=2");
        exit;
    } else {
        $error_message = "Error al actualizar el horario.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Horario</title>
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<header>
    <h1><a href="../index.php" class="header-link">CharlOs Streetwear</a></h1>
</header>

<div class="container mt-4 content-container">
    <h2 class="text-center mb-4">Modificar Horario de Empleado</h2>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if ($horario): ?>
    <form action="editar.php" method="POST">
        <input type="hidden" name="id_horario" value="<?php echo htmlspecialchars($horario['id_horario']); ?>">

        <div class="mb-3">
            <label for="id_empleado" class="form-label">Empleado:</label>
            <select name="id_empleado" id="id_empleado" class="form-control" required>
                <?php while ($row = $empleados->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_empleado']; ?>" 
                        <?php echo ($horario['id_empleado'] == $row['id_empleado']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['nombre_empleado'] . " " . $row['apellido_paterno']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="hora_entrada" class="form-label">Hora de Entrada:</label>
            <input type="time" name="hora_entrada" id="hora_entrada" class="form-control"
                   value="<?php echo htmlspecialchars($horario['hora_entrada']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="hora_salida" class="form-label">Hora de Salida:</label>
            <input type="time" name="hora_salida" id="hora_salida" class="form-control"
                   value="<?php echo htmlspecialchars($horario['hora_salida']); ?>" required>
        </div>

        <button type="submit" class="btn btn-warning">Actualizar Horario</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
