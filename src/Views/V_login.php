<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Ingreso - DALU</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="icon" href="assets/img/dalulisto.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>

    <div class="overlay"></div>

    <div class="login-wrapper">
        <div class="glass-card">
            
            <div class="logo-container">
                <img src="assets/img/dalulisto.png" alt="Logo DALU">
            </div>
            
            <h1 class="title">BIENVENIDO</h1>
            
            <form action="?c=login&accion=login" method="POST">
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-custom" role="alert">
                        <?= htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="form-group mb-4">
                    <label for="usuario">Usuario</label>
                    <input type="text" name="usuario" class="form-control custom-input" id="usuario" placeholder="Ingrese su usuario" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" class="form-control custom-input" id="password" placeholder="Ingrese su contraseña" required>
                </div>

                <div class="mb-3">
                    <a href="?c=recoverpass&accion=view" class="text-decoration-none" style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn-login">INGRESAR</button>
                </div>
            </form>

        </div>
    </div>

</body>

</html>