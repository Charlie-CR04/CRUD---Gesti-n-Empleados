<?php
include "../../includes/conexion.php";

// Consulta con JOIN para obtener nombre del empleado
$sql = "
SELECT n.id_nomina, n.fecha_pago, n.salario_base, n.bonos, n.descuentos, n.salario_neto,
       e.nombre_empleado, e.apellido_paterno, e.apellido_materno
FROM nomina n
INNER JOIN empleados e ON n.id_empleado = e.id_empleado
ORDER BY n.id_nomina ASC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Nómina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../styles/all.min.css">
    <link rel="stylesheet" href="../../styles/styles.css">
</head>
<body>
    <header>
        <h1><a href="../../index.php" class="header-link">CharlOs Streetwear</a></h1>
    </header>

    <div class="container mt-4 content-container">
        <h2 class="text-center mb-4">Listado de Nómina</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php if ($_GET['success'] == 1): ?>
                    Nómina registrada correctamente.
                <?php elseif ($_GET['success'] == 2): ?>
                    Nómina actualizada correctamente.
                <?php elseif ($_GET['success'] == 3): ?>
                    Nómina eliminada correctamente.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php if ($_GET['error'] == 'notfound'): ?>
                    Registro no encontrado.
                <?php elseif ($_GET['error'] == 'relation'): ?>
                    No se puede eliminar, tiene pagos vinculados.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <a href="crear.php" class="btn btn-primary mb-3">
            <i class="fa-solid fa-circle-plus"></i> Agregar Nómina
        </a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Empleado</th>
                        <th>Fecha de Pago</th>
                        <th>Salario Base</th>
                        <th>Bonos</th>
                        <th>Descuentos</th>
                        <th>Salario Neto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["id_nomina"]); ?></td>
                        <td><?php echo htmlspecialchars($row["nombre_empleado"] . " " . $row["apellido_paterno"] . " " . $row["apellido_materno"]); ?></td>
                        <td><?php echo htmlspecialchars($row["fecha_pago"]); ?></td>
                        <td>$<?php echo number_format($row["salario_base"], 2); ?></td>
                        <td>$<?php echo number_format($row["bonos"], 2); ?></td>
                        <td>$<?php echo number_format($row["descuentos"], 2); ?></td>
                        <td>$<?php echo number_format($row["salario_neto"], 2); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $row["id_nomina"]; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="eliminar.php?id=<?php echo $row["id_nomina"]; ?>" class="btn btn-danger btn-sm delete-button">
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
        <p>&copy; 2024 Gestión de Nómina - Todos los derechos reservados.</p>
    </footer>

    <script src="../../scripts/dinamico.js"></script>
</body>
</html>
