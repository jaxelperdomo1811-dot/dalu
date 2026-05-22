<!DOCTYPE html>
<html>

<head>
    <title>Dalu Boutique</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <script src="assets/js/js.js" defer></script>
</head>

<body>

    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>

        <div>
            <h1 class="titulo">Bienvenidos</h1>
        </div>
        <hr>

        <div class="container my-6">
            <div class="row justify-content-center g-4">
                <!-- Carta Pacientes -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-primary rounded h-100 hover-shadow" style="max-width: 18rem;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title fst-italic text-primary mb-4">Clientes</h5>
                                <p class="card-text fs-4"><?php echo $cantidadPacientes; ?> Clientes registrados.</p>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top d-flex justify-content-end">
                            <a href="?c=pacientes" class="btn btn-outline-primary btn-sm">Ir a Clientes</a>
                        </div>
                    </div>
                </div>

                <!-- Carta Doctores -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-success rounded h-100 hover-shadow" style="max-width: 18rem;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title fst-italic text-success mb-3">Pedidos</h5>
                                <p class="card-text fs-4"><?php echo $cantidadDoctores; ?> pedidos registrados.</p>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top d-flex justify-content-center">
                            <a href="?c=pedidos" class="btn btn-outline-danger btn-sm">Ir a Pedidos</a>
                        </div>
                    </div>
                </div>

                <!-- Carta Usuarios -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-info rounded h-100 hover-shadow" style="max-width: 18rem;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title fst-italic text-info mb-3">Usuarios</h5>
                                <p class="card-text fs-4"><?php echo $cantidadUsuarios; ?> usuarios registrados.</p>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top d-flex justify-content-end">
                            <a href="?c=usuarios" class="btn btn-outline-info btn-sm">Ir a Usuarios</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agrega estos estilos en tu CSS o en un bloque style -->
        <style>
            .hover-shadow:hover {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
                transition: box-shadow 0.3s ease;
            }
        </style>
    </main>

</body>

</html>