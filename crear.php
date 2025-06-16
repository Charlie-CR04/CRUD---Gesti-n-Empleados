<?php
include "../includes/conexion.php";

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = trim($_POST['id_departamento']);
    $nombre = trim($_POST['nombre_departamento']);

    // Validar campos obligatorios y numéricos
    if (!is_numeric($id) || empty($nombre)) {
        $error_message = "ID inválido o nombre vacío.";
    } else {
        // Verificar si el ID ya existe
        $stmt_check_id = $conn->prepare("SELECT COUNT(*) AS total FROM departamentos WHERE id_departamento = ?");
        $stmt_check_id->bind_param("i", $id);
        $stmt_check_id->execute();
        $res_id = $stmt_check_id->get_result()->fetch_assoc();
        $stmt_check_id->close();

        if ($res_id['total'] > 0) {
            $error_message = "El ID ya está en uso.";
        } else {
            // Verificar si el nombre ya existe
            $stmt_check_nombre = $conn->prepare("SELECT COUNT(*) AS total FROM departamentos WHERE nombre_departamento = ?");
            $stmt_check_nombre->bind_param("s", $nombre);
            $stmt_check_nombre->execute();
            $res_nombre = $stmt_check_nombre->get_result()->fetch_assoc();
            $stmt_check_nombre->close();

            if ($res_nombre['total'] > 0) {
                $error_message = "El nombre del departamento ya existe.";
            } else {
                // Insertar nuevo departamento
                $stmt_insert = $conn->prepare("INSERT INTO departamentos (id_departamento, nombre_departamento) VALUES (?, ?)");
                $stmt_insert->bind_param("is", $id, $nombre);

                if ($stmt_insert->execute()) {
                    $stmt_insert->close();
                    header("Location: index.php?success=1");
                    exit;
                } else {
                    $error_message = "Error al agregar el departamento.";
                }

                $stmt_insert->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Departamento</title>
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
        <h2 class="text-center mb-4">Registrar Departamento</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="crear.php" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="id_departamento" class="form-label">ID del Departamento:</label>
                <input type="number" id="id_departamento" name="id_departamento" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="nombre_departamento" class="form-label">Nombre del Departamento:</label>
                <input type="text" id="nombre_departamento" name="nombre_departamento" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="../scripts/validaciones.js"></script>
</body>
</html>
