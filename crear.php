<?php
include "../includes/conexion.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_empleado = trim($_POST['id_empleado']);
    $nombre = trim($_POST['nombre_empleado']);
    $ap_paterno = trim($_POST['apellido_paterno']);
    $ap_materno = trim($_POST['apellido_materno']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $direccion = trim($_POST['direccion']);
    $estado = $_POST['estado'];
    $id_departamento = $_POST['id_departamento'];
    $id_puesto = $_POST['id_puesto'];

    // Verificar si ya existe el ID del empleado
    $verificar = $conn->prepare("SELECT COUNT(*) AS total FROM empleados WHERE id_empleado = ?");
    $verificar->bind_param("i", $id_empleado);
    $verificar->execute();
    $existe = $verificar->get_result()->fetch_assoc()["total"];
    $verificar->close();

    if ($existe > 0) {
        $error_message = "El ID del empleado ya existe.";
    } else {
        $sql = "INSERT INTO empleados (id_empleado, id_departamento, id_puesto, nombre_empleado, apellido_paterno, apellido_materno, telefono, email, direccion, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiisssssss", $id_empleado, $id_departamento, $id_puesto, $nombre, $ap_paterno, $ap_materno, $telefono, $email, $direccion, $estado);
        
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php?success=1");
            exit;
        } else {
            $error_message = "Error al registrar el empleado.";
        }
    }
}

// Obtener departamentos y puestos para los selects
$departamentos = $conn->query("SELECT id_departamento, nombre_departamento FROM departamentos ORDER BY nombre_departamento ASC");
$puestos = $conn->query("SELECT id_puesto, nombre_puesto FROM puestos ORDER BY nombre_puesto ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Empleado</title>
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <header>
        <h1><a href="../index.php" class="header-link">CharlOs Streetwear</a></h1>
    </header>

    <div class="container mt-4 content-container">
        <h2 class="text-center mb-4">Registrar Empleado</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="crear.php" method="POST">
            <div class="mb-3">
                <label for="id_empleado" class="form-label">ID del Empleado:</label>
                <input type="number" class="form-control" name="id_empleado" required>
            </div>
            <div class="mb-3">
                <label for="nombre_empleado" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre_empleado" required>
            </div>
            <div class="mb-3">
                <label for="apellido_paterno" class="form-label">Apellido Paterno:</label>
                <input type="text" class="form-control" name="apellido_paterno" required>
            </div>
            <div class="mb-3">
                <label for="apellido_materno" class="form-label">Apellido Materno:</label>
                <input type="text" class="form-control" name="apellido_materno" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="text" class="form-control" name="telefono" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección:</label>
                <textarea class="form-control" name="direccion" rows="2" required></textarea>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select class="form-control" name="estado" required>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_departamento" class="form-label">Departamento:</label>
                <select class="form-control" name="id_departamento" required>
                    <?php while ($d = $departamentos->fetch_assoc()): ?>
                        <option value="<?php echo $d['id_departamento']; ?>"><?php echo $d['nombre_departamento']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_puesto" class="form-label">Puesto:</label>
                <select class="form-control" name="id_puesto" required>
                    <?php while ($p = $puestos->fetch_assoc()): ?>
                        <option value="<?php echo $p['id_puesto']; ?>"><?php echo $p['nombre_puesto']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
