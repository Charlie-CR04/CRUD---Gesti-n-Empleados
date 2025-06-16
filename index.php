<?php
include "../includes/conexion.php";

// Consulta para obtener empleados con info de puesto y departamento
$sql = "
SELECT e.id_empleado, e.nombre_empleado, e.apellido_paterno, e.apellido_materno,
       p.nombre_puesto, d.nombre_departamento, e.estado
FROM empleados e
INNER JOIN puestos p ON e.id_puesto = p.id_puesto
INNER JOIN departamentos d ON e.id_departamento = d.id_departamento
ORDER BY e.id_empleado ASC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Empleados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <header>
        <h1><a href="../index.php" class="header-link">CharlOs Streetwear</a></h1>
    </header>

    <div class="container mt-4 content-container">
        <h2 class="text-center mb-4">Listado de Empleados</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php if ($_GET['success'] == 1): ?>
                    Empleado agregado correctamente.
                <?php elseif ($_GET['success'] == 2): ?>
                    Empleado actualizado correctamente.
                <?php elseif ($_GET['success'] == 3): ?>
                    Empleado eliminado correctamente.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php if ($_GET['error'] == 'relation'): ?>
                    No se puede eliminar, hay registros relacionados con este empleado.
                <?php elseif ($_GET['error'] == 'notfound'): ?>
                    Empleado no encontrado.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <a href="crear.php" class="btn btn-primary mb-3">
            <i class="fa-solid fa-circle-plus"></i> Agregar Empleado
        </a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Puesto</th>
                        <th>Departamento</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["id_empleado"]); ?></td>
                        <td><?php echo htmlspecialchars($row["nombre_empleado"] . " " . $row["apellido_paterno"] . " " . $row["apellido_materno"]); ?></td>
                        <td><?php echo htmlspecialchars($row["nombre_puesto"]); ?></td>
                        <td><?php echo htmlspecialchars($row["nombre_departamento"]); ?></td>
                        <td><?php echo htmlspecialchars($row["estado"]); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $row["id_empleado"]; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="eliminar.php?id=<?php echo $row["id_empleado"]; ?>" class="btn btn-danger btn-sm delete-button">
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
        <p>&copy; 2024 Gestión de Empleados - Todos los derechos reservados.</p>
    </footer>

    <script src="../scripts/dinamico.js"></script>
    <script src="../scripts/bootstrap.bundle.min.js"></script>
</body>
</html>
