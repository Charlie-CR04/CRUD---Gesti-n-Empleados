<?php
include "../includes/conexion.php";

// Consulta para obtener los departamentos
$sql = "SELECT * FROM departamentos ORDER BY id_departamento ASC";
$result = $conn->query($sql);

// Validación de la consulta
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Departamentos</title>
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
        <h2 class="text-center mb-4">Listado de Departamentos</h2>

        <!-- Mensajes dinámicos -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php if ($_GET['success'] == 1): ?>
                    Departamento agregado correctamente.
                <?php elseif ($_GET['success'] == 2): ?>
                    Departamento actualizado correctamente.
                <?php elseif ($_GET['success'] == 3): ?>
                    Departamento eliminado correctamente.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php if ($_GET['error'] == 'relation'): ?>
                    No se puede eliminar, hay empleados en este departamento.
                <?php elseif ($_GET['error'] == 'notfound'): ?>
                    Departamento no encontrado.
                <?php elseif ($_GET['error'] == 'delete'): ?>
                    Error al intentar eliminar el departamento.
                <?php endif; ?>
            </div>
        <?php endif; ?>


        <!-- Botón para agregar -->
        <a href="crear.php" class="btn btn-primary mb-3">
            <i class="fa-solid fa-circle-plus"></i> Agregar Departamento
        </a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["id_departamento"]); ?></td>
                        <td><?php echo htmlspecialchars($row["nombre_departamento"]); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $row["id_departamento"]; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="eliminar.php?id=<?php echo $row["id_departamento"]; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Estás seguro de eliminar este departamento?');">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Gestión de Departamentos - Todos los derechos reservados.</p>
    </footer>

    <script src="../scripts/dinamico.js"></script>
</body>
</html>
