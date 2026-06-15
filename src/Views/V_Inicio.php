<!DOCTYPE html>
<html>

<head>
    <title>Dalu Boutique - Inicio</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/inicio.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="icon" href="assets/img/dalulisto.png">
    <script src="assets/js/js.js" defer></script>
    <script src="assets/js/libs/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main class="dashboard-container container-fluid">
        
        <h1 class="welcome-header">Inicio</h1>

        <div class="row g-4 mt-2">
            
            <div class="col-md-4">
                <div class="card stat-card card-clientes h-100">
                    <div class="card-body">
                        <svg class="icon-bg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                        <h5 class="card-title">Total Clientes</h5>
                        <p class="card-text text-white"><?php echo htmlspecialchars($cantidadClientes ?? 0); ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="?c=clientes" class="btn-link">Ir a Clientes <span style="margin-left:5px;">&rarr;</span></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card card-productos h-100">
                    <div class="card-body">
                        <svg class="icon-bg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 16.5c0 .38-.21.71-.53.88l-7.9 4.44c-.16.12-.36.18-.57.18s-.41-.06-.57-.18l-7.9-4.44A.991.991 0 0 1 3 16.5v-9c0-.38.21-.71.53-.88l7.9-4.44c.16-.12.36-.18.57-.18s.41.06.57.18l7.9 4.44c.32.17.53.5.53.88v9zM12 4.15L6.04 7.5 12 10.85l5.96-3.35L12 4.15zM5 15.91l6 3.38v-6.71L5 9.19v6.72zm14 0v-6.72l-6 3.39v6.71l6-3.38z"/></svg>
                        <h5 class="card-title">Total Productos</h5>
                        <p class="card-text text-white"><?php echo htmlspecialchars($cantidadProductos ?? 0); ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="?c=productos" class="btn-link">Ir a Productos <span style="margin-left:5px;">&rarr;</span></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card card-usuarios h-100">
                    <div class="card-body">
                        <svg class="icon-bg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4.5c1.38 0 2.5 1.12 2.5 2.5s-1.12 2.5-2.5 2.5-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5zM12 18c-2.43 0-4.63-1.07-6.14-2.78.29-1.28 2.87-2.22 6.14-2.22s5.85.94 6.14 2.22C16.63 16.93 14.43 18 12 18z"/></svg>
                        <h5 class="card-title">Usuarios del Sistema</h5>
                        <p class="card-text text-white"><?php echo htmlspecialchars($cantidadUsuarios ?? 0); ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="?c=usuarios" class="btn-link">Ir a Usuarios <span style="margin-left:5px;">&rarr;</span></a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>