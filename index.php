<?php
include "includes/conexion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CharlOs Streetwear</title>
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="styles/all.min.css">
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        /* Fondo con imagen */
        body {
            background-image: url('imagenes/fondo-index.jpg'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* Contenedor principal con fondo semi-transparente */
        .content-container {
            background-color: rgba(255, 255, 255, 0.85); /* Mayor opacidad */
            padding: 30px;
            border-radius: 10px;
            max-width: 1100px; /* Limitar ancho */
            margin: auto; /* Centrar */
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Tarjetas con mejor espaciado */
        .card {
            border-radius: 10px;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.03);
        }
        .card-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

    </style>
</head>
<body>
    <header>
        <h1>
            <a href="../gestion-empleados/index.php" class="header-link">CharlOs Streetwear</a>
        </h1>
    </header>

    <div class="container mt-4 content-container">
        <div class="row text-center justify-content-center">
            <!-- EMPLEADOS -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header text-white" style="background-color: #0066FF;">
                        <h5><i class="fas fa-user-tie"></i> Empleados</h5>
                    </div>
                    <div class="card-body">
                        <p>Gestión de empleados de la empresa.</p>
                        <a href="empleados/index.php" class="btn text-white" style="background-color: #0066FF;">Ver Empleados</a>
                    </div>
                </div>
            </div>

            <!-- DEPARTAMENTOS -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header text-white" style="background-color: #007A33;">
                        <h5><i class="fas fa-building"></i> Departamentos</h5>
                    </div>
                    <div class="card-body">
                        <p>Áreas dentro de la empresa.</p>
                        <a href="departamentos/index.php" class="btn text-white" style="background-color: #007A33;">Ver Departamentos</a>
                    </div>
                </div>
            </div>

            <!-- PUESTOS -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header text-white" style="background-color: #FFC107;">
                        <h5><i class="fas fa-briefcase"></i> Puestos</h5>
                    </div>
                    <div class="card-body">
                        <p>Cargos dentro de cada departamento.</p>
                        <a href="puestos/index.php" class="btn text-white" style="background-color: #FFC107;">Ver Puestos</a>
                    </div>
                </div>
            </div>

            <!-- HORARIOS -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header text-white" style="background-color: #00BFFF;">
                        <h5><i class="fas fa-clock"></i> Horarios</h5>
                    </div>
                    <div class="card-body">
                        <p>Turnos de los empleados.</p>
                        <a href="horarios/index.php" class="btn text-white" style="background-color: #00BFFF;">Ver Horarios</a>
                    </div>
                </div>
            </div>

            <!-- ASISTENCIAS -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header text-white" style="background-color: #DC3545;">
                        <h5><i class="fas fa-calendar-check"></i> Asistencias</h5>
                    </div>
                    <div class="card-body">
                        <p>Registro de asistencia de empleados.</p>
                        <a href="asistencias/index.php" class="btn text-white" style="background-color: #DC3545;">Ver Asistencias</a>
                    </div>
                </div>
            </div>

            <!-- FINANZAS -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header text-white" style="background-color: #6C757D;">
                        <h5><i class="fas fa-money-bill-wave"></i> Finanzas</h5>
                    </div>
                    <div class="card-body">
                        <p>Pagos y Nominas de empleados.</p>
                        <a href="finanzas/indexPN.php" class="btn text-white" style="background-color: #6C757D;">Ver Finanzas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Gestión de Empleados - Todos los derechos reservados.</p>
    </footer>

    <script src="scripts/bootstrap.bundle.min.js"></script>
</body>
</html>
