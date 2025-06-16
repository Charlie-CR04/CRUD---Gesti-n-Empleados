<?php
include "../includes/conexion.php";

$error_message = "";

// Validar ID recibido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?error=notfound");
    exit;
}

$id_puesto = intval($_GET['id']);

// Obtener datos actuales del puesto
$sql = "SELECT * FROM puestos WHERE id_puesto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_puesto);
$stmt->execute();
$result = $stmt->get_result();
$puesto = $result->fetch_assoc();
$stmt->close();

if (!$puesto) {
    header("Location: index.php?error=notfound");
    exit;
}

// Obtener departamentos
$sql_deptos = "SELECT id_departamento, nombre_departamento FROM departamentos ORDER BY nombre_departamento ASC";
$departamentos = $conn->query($sql_deptos);

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_nombre = trim($_POST["nombre_puesto"]);
    $nuevo_departamento = intval($_POST["id_departamento"]);

    if (empty($nuevo_nombre)) {
        $error_message = "El nombre del puesto no puede estar vacío.";
    } else {
        // Verificar que no haya otro puesto con el mismo nombre (excepto el actual)
        $sql_verificar = "SELECT COUNT(*) AS total FROM puestos WHERE nombre_puesto = ? AND id_puesto != ?";
        $stmt = $conn->prepare($sql_verificar);
        $stmt->bind_param("si", $nuevo_nombre, $id_puesto);
        $stmt->execute();
        $existe = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        if ($existe > 0) {
            $error_message = "Ya existe un puesto con ese nombre.";
        } else {
            $sql_update = "UPDATE puestos SET nombre_puesto = ?, id_departamento = ? WHERE id_puesto = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("sii", $nuevo_nombre, $nuevo_departamento, $id_puesto);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: index.php?success=2");
                exit;
            } else {
                $error_message = "Error al actualizar el puesto.";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Puesto</title>
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <header>
        <h1><a href="../index.php" class="header-link">CharlOs Streetwear</a></h1>
    </header>

    <div class="container mt-4 content-container">
        <h2 class="text-center mb-4">Editar Puesto</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="id_puesto" class="form-label">ID del Puesto:</label>
                <input type="text" id="id_puesto" class="form-control" value="<?php echo $puesto['id_puesto']; ?>" disabled>
            </div>

            <div class="mb-3">
                <label for="nombre_puesto" class="form-label">Nombre del Puesto:</label>
                <input type="text" name="nombre_puesto" id="nombre_puesto" class="form-control" required value="<?php echo htmlspecialchars($puesto['nombre_puesto']); ?>">
            </div>

            <div class="mb-3">
                <label for="id_departamento" class="form-label">Departamento:</label>
                <select name="id_departamento" id="id_departamento" class="form-select" required>
                    <?php while ($row = $departamentos->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_departamento']; ?>" <?php if ($row['id_departamento'] == $puesto['id_departamento']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row['nombre_departamento']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="../scripts/validaciones.js"></script>
</body>
</html>
