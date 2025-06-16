<?php
include "../includes/conexion.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=notfound");
    exit;
}

$id = $_GET['id'];
$error_message = "";

// Obtener los datos del empleado
$sql = "SELECT * FROM empleados WHERE id_empleado = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$empleado = $resultado->fetch_assoc();
$stmt->close();

if (!$empleado) {
    header("Location: index.php?error=notfound");
    exit;
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre_empleado']);
    $ap_paterno = trim($_POST['apellido_paterno']);
    $ap_materno = trim($_POST['apellido_materno']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $direccion = trim($_POST['direccion']);
    $estado = $_POST['estado'];
    $id_departamento = $_POST['id_departamento'];
    $id_puesto = $_POST['id_puesto'];

    $sql_update = "UPDATE empleados SET id_departamento=?, id_puesto=?, nombre_empleado=?, apellido_paterno=?, apellido_materno=?, telefono=?, email=?, direccion=?, estado=? WHERE id_empleado=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("iisssssssi", $id_departamento, $id_puesto, $nombre, $ap_paterno, $ap_materno, $telefono, $email, $direccion, $estado, $id);

    if ($stmt_update->execute()) {
        $stmt_update->close();
        header("Location: index.php?success=2");
        exit;
    } else {
        $error_message = "Error al actualizar el empleado.";
    }
}

// Obtener datos para selects
$departamentos = $conn->query("SELECT id_departamento, nombre_departamento FROM departamentos ORDER BY nombre_departamento ASC");
$puestos = $conn->query("SELECT id_puesto, nombre_puesto FROM puestos ORDER BY nombre_puesto ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Empleado</title>
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <header>
        <h1><a href="../index.php" class="header-link">CharlOs Streetwear</a></h1>
    </header>

    <div class="container mt-4 content-container">
        <h2 class="text-center mb-4">Editar Empleado</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="editar.php?id=<?php echo $id; ?>" method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre:</label>
                <input type="text" name="nombre_empleado" class="form-control" value="<?php echo htmlspecialchars($empleado['nombre_empleado']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Apellido Paterno:</label>
                <input type="text" name="apellido_paterno" class="form-control" value="<?php echo htmlspecialchars($empleado['apellido_paterno']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Apellido Materno:</label>
                <input type="text" name="apellido_materno" class="form-control" value="<?php echo htmlspecialchars($empleado['apellido_materno']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Teléfono:</label>
                <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($empleado['telefono']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Correo:</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($empleado['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Dirección:</label>
                <textarea name="direccion" class="form-control" required><?php echo htmlspecialchars($empleado['direccion']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado:</label>
                <select name="estado" class="form-control" required>
                    <option value="Activo" <?php if ($empleado['estado'] == 'Activo') echo 'selected'; ?>>Activo</option>
                    <option value="Inactivo" <?php if ($empleado['estado'] == 'Inactivo') echo 'selected'; ?>>Inactivo</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Departamento:</label>
                <select name="id_departamento" class="form-control" required>
                    <?php while ($d = $departamentos->fetch_assoc()): ?>
                        <option value="<?php echo $d['id_departamento']; ?>" <?php if ($empleado['id_departamento'] == $d['id_departamento']) echo 'selected'; ?>>
                            <?php echo $d['nombre_departamento']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Puesto:</label>
                <select name="id_puesto" class="form-control" required>
                    <?php while ($p = $puestos->fetch_assoc()): ?>
                        <option value="<?php echo $p['id_puesto']; ?>" <?php if ($empleado['id_puesto'] == $p['id_puesto']) echo 'selected'; ?>>
                            <?php echo $p['nombre_puesto']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <script src="scripts/bootstrap.bundle.min.js"></script>
</body>
</html>
