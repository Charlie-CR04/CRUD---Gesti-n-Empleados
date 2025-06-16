<?php
include "../includes/conexion.php";

$error_message = "";
$success_message = "";

// Validar que se proporcione un ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?error=notfound");
    exit;
}

$id = intval($_GET['id']);

// Buscar el departamento a editar
$sql = "SELECT * FROM departamentos WHERE id_departamento = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();

if ($resultado->num_rows == 0) {
    header("Location: index.php?error=notfound");
    exit;
}

$departamento = $resultado->fetch_assoc();

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_nombre = trim($_POST['nombre_departamento']);

    if (empty($nuevo_nombre)) {
        $error_message = "El nombre no puede estar vacío.";
    } else {
        // Verificar si ya existe otro departamento con ese nombre
        $sql_verificar = "SELECT COUNT(*) AS total FROM departamentos WHERE nombre_departamento = ? AND id_departamento != ?";
        $stmt = $conn->prepare($sql_verificar);
        $stmt->bind_param("si", $nuevo_nombre, $id);
        $stmt->execute();
        $resultado_verificar = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($resultado_verificar['total'] > 0) {
            $error_message = "Ya existe un departamento con ese nombre.";
        } else {
            // Actualizar el nombre del departamento
            $sql_update = "UPDATE departamentos SET nombre_departamento = ? WHERE id_departamento = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("si", $nuevo_nombre, $id);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: index.php?success=2");
                exit;
            } else {
                $error_message = "Error al actualizar el departamento.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Departamento</title>
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
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
        <h2 class="text-center mb-4">Editar Departamento</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="editar.php?id=<?php echo $id; ?>" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="id_departamento" class="form-label">ID del Departamento:</label>
                <input type="text" id="id_departamento" name="id_departamento" class="form-control" value="<?php echo htmlspecialchars($departamento['id_departamento']); ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="nombre_departamento" class="form-label">Nombre del Departamento:</label>
                <input type="text" id="nombre_departamento" name="nombre_departamento" class="form-control" value="<?php echo htmlspecialchars($departamento['nombre_departamento']); ?>" required>
            </div>

            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="../scripts/validaciones.js"></script>
</body>
</html>
