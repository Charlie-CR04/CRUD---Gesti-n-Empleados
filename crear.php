<?php
include "../includes/conexion.php";

$error_message = "";
$success_message = "";

// Obtener los departamentos para el select
$sql_deptos = "SELECT id_departamento, nombre_departamento FROM departamentos ORDER BY nombre_departamento ASC";
$result_deptos = $conn->query($sql_deptos);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_puesto = intval($_POST['id_puesto']);
    $nombre = trim($_POST['nombre_puesto']);
    $id_departamento = intval($_POST['id_departamento']);

    if (empty($nombre)) {
        $error_message = "El nombre del puesto no puede estar vacío.";
    } else {
        // Verificar si ya existe ese ID o nombre
        $sql_verificar = "SELECT COUNT(*) AS total FROM puestos WHERE id_puesto = ? OR nombre_puesto = ?";
        $stmt = $conn->prepare($sql_verificar);
        $stmt->bind_param("is", $id_puesto, $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($resultado['total'] > 0) {
            $error_message = "Ya existe un puesto con ese ID o nombre.";
        } else {
            // Insertar
            $sql_insert = "INSERT INTO puestos (id_puesto, id_departamento, nombre_puesto) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("iis", $id_puesto, $id_departamento, $nombre);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: index.php?success=1");
                exit;
            } else {
                $error_message = "Error al agregar el puesto.";
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
    <title>Agregar Puesto</title>
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <header>
        <h1><a href="../index.php" class="header-link">CharlOs Streetwear</a></h1>
    </header>

    <div class="container mt-4 content-container">
        <h2 class="text-center mb-4">Registrar Puesto</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="crear.php" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="id_puesto" class="form-label">ID del Puesto:</label>
                <input type="number" id="id_puesto" name="id_puesto" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="nombre_puesto" class="form-label">Nombre del Puesto:</label>
                <input type="text" id="nombre_puesto" name="nombre_puesto" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="id_departamento" class="form-label">Departamento:</label>
                <select name="id_departamento" id="id_departamento" class="form-select" required>
                    <option value="">Seleccione un departamento</option>
                    <?php while ($row = $result_deptos->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_departamento']; ?>">
                            <?php echo htmlspecialchars($row['nombre_departamento']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="../scripts/validaciones.js"></script>
</body>
</html>
