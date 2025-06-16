<?php
include "../includes/conexion.php";

// Consulta para obtener las asistencias con los nombres de empleados
$sql = "SELECT a.id_asistencia, e.nombre_empleado, e.apellido_paterno, a.fecha, a.hora_entrada, a.hora_salida, a.estado 
        FROM asistencias a
        JOIN empleados e ON a.id_empleado = e.id_empleado
        ORDER BY id_asistencia ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Asistencias</title>
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
        <h2 class="text-center mb-4">Listado de Asistencias</h2>

        <!-- Mensajes dinámicos -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php if ($_GET['success'] == 1): ?>
                    Asistencia registrada correctamente.
                <?php elseif ($_GET['success'] == 2): ?>
                    Asistencia actualizada correctamente.
                <?php elseif ($_GET['success'] == 3): ?>
                    Asistencia eliminada correctamente.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Botón para agregar nueva asistencia -->
        <a href="crear.php" class="btn btn-primary mb-3">
            <i class="fa-solid fa-circle-plus"></i> Registrar Asistencia
        </a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Empleado</th>
                        <th>Fecha</th>
                        <th>Hora Entrada</th>
                        <th>Hora Salida</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["id_asistencia"]); ?></td>
                        <td><?php echo htmlspecialchars($row["nombre_empleado"] . " " . $row["apellido_paterno"]); ?></td>
                        <td><?php echo htmlspecialchars($row["fecha"]); ?></td>
                        <td><?php echo htmlspecialchars($row["hora_entrada"]); ?></td>
                        <td><?php echo htmlspecialchars($row["hora_salida"]); ?></td>
                        <td>
                            <?php if ($row["estado"] == "Presente"): ?>
                                <span class="badge bg-success">Presente</span>
                            <?php elseif ($row["estado"] == "Tarde"): ?>
                                <span class="badge bg-warning text-dark">Tarde</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Ausente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="editar.php?id=<?php echo $row["id_asistencia"]; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="eliminar.php?id=<?php echo $row["id_asistencia"]; ?>" class="btn btn-danger btn-sm delete-button">
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
        <p>&copy; 2024 Gestión de Asistencias - Todos los derechos reservados.</p>
    </footer>

    <script src="../scripts/dinamico.js"></script>
</body>
</html>
