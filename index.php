<?php
include "../includes/conexion.php";

// Consulta para obtener los horarios de los empleados
$sql = "SELECT h.id_horario, e.nombre_empleado, e.apellido_paterno, h.hora_entrada, h.hora_salida 
        FROM horarios h
        JOIN empleados e ON h.id_empleado = e.id_empleado
        ORDER BY h.id_horario ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Horarios</title>
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
        <h2 class="text-center mb-4">Listado de Horarios</h2>

        <!-- Mensajes dinámicos -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php if ($_GET['success'] == 1): ?>
                    Horario agregado correctamente.
                <?php elseif ($_GET['success'] == 2): ?>
                    Horario actualizado correctamente.
                <?php elseif ($_GET['success'] == 3): ?>
                    Horario eliminado correctamente.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php if ($_GET['error'] == 'notfound'): ?>
                    Horario no encontrado.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Botón para agregar -->
        <a href="crear.php" class="btn btn-primary mb-3">
            <i class="fa-solid fa-circle-plus"></i> Asignar Horario
        </a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Empleado</th>
                        <th>Hora Entrada</th>
                        <th>Hora Salida</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["id_horario"]); ?></td>
                        <td><?php echo htmlspecialchars($row["nombre_empleado"] . " " . $row["apellido_paterno"]); ?></td>
                        <td><?php echo htmlspecialchars($row["hora_entrada"]); ?></td>
                        <td><?php echo htmlspecialchars($row["hora_salida"]); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $row["id_horario"]; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="eliminar.php?id=<?php echo $row["id_horario"]; ?>" class="btn btn-danger btn-sm delete-button">
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
        <p>&copy; 2024 Gestión de Horarios - Todos los derechos reservados.</p>
    </footer>

    <script src="../scripts/dinamico.js"></script>
</body>
</html>
