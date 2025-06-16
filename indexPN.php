<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nómina y Pagos</title>
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
        <div class="row text-center justify-content-center">
            <!-- NOMINA -->
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header text-white" style="background-color: #00BFFF;">
                        <h5><i class="fas fa-money-bill-wave"></i> Nómina</h5>
                    </div>
                    <div class="card-body">
                        <p>Gestión de sueldos de los empleados.</p>
                        <a href="nomina/index.php" class="btn text-white" style="background-color: #00BFFF;">Ver Sueldos</a>
                    </div>
                </div>
            </div>

            <!-- PAGOS -->
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header text-white" style="background-color: #0066FF;">
                        <h5><i class="fas fa-credit-card"></i> Pagos</h5>
                    </div>
                    <div class="card-body">
                        <p>Administración de pagos realizados.</p>
                        <a href="pagos/index.php" class="btn text-white" style="background-color: #0066FF;">Ver Pagos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Gestión de Nómina - Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
