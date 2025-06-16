<?php
include "../includes/conexion.php";

// Consulta para obtener puestos con el nombre del departamento
$sql = "SELECT puestos.id_puesto, puestos.nombre_puesto, departamentos.nombre_departamento 
        FROM puestos 
        JOIN departamentos ON puestos.id_departamento = departamentos.id_departamento 
        ORDER BY id_puesto ASC";
$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Puestos</title>
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <header>
        <h1><a href="../index.php" class="header-link">CharlOs Streetwear</a></h1>
    </header>

    <div class="container mt-4 content-container">
        <h2 class="text-center mb-4">Listado de Puestos</h2>

        <!-- Mensajes dinámicos -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php if ($_GET['success'] == 1): ?>
                    Puesto agregado correctamente.
                <?php elseif ($_GET['success'] == 2): ?>
                    Puesto actualizado correctamente.
                <?php elseif ($_GET['success'] == 3): ?>
                    Puesto eliminado correctamente.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php if ($_GET['error'] == 'relation'): ?>
                    No se puede eliminar, hay empleados asignados a este puesto.
                <?php elseif ($_GET['error'] == 'notfound'): ?>
                    Puesto no encontrado.
                <?php elseif ($_GET['error'] == 'delete'): ?>
                    Error al intentar eliminar el puesto.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <a href="crear.php" class="btn btn-primary mb-3">
            <i class="fa-solid fa-circle-plus"></i> Agregar Puesto
        </a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Puesto</th>
                        <th>Departamento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["id_puesto"]); ?></td>
                        <td><?php echo htmlspecialchars($row["nombre_puesto"]); ?></td>
                        <td><?php echo htmlspecialchars($row["nombre_departamento"]); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $row["id_puesto"]; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="eliminar.php?id=<?php echo $row["id_puesto"]; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Estás seguro de eliminar este puesto?');">
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
        <p>&copy; 2024 Gestión de Puestos - Todos los derechos reservados.</p>
    </footer>

    <script src="../scripts/dinamico.js"></script>
</body>
</html>
