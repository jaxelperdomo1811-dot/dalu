<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recuperar Contraseña - DALU</title>
        <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="icon" href="assets/img/dalulisto.png">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="assets/js/"></script>
    
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-image: url('assets/img/fondodalu.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        /* Capa oscura superpuesta para que el fondo no compita con el login */
        .overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        /* Contenedor principal alineado al centro */
        .login-wrapper {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Glassmorphism para la tarjeta de login */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 120px;
            filter: drop-shadow(0px 4px 6px rgba(0,0,0,0.3));
        }

        .title {
            text-align: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 30px;
            letter-spacing: 2px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        .form-group label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: block;
        }

        .custom-input {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            box-shadow: none;
            width: 100%;
        }

        .custom-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .custom-input:focus {
            background: rgba(0, 0, 0, 0.5);
            border-color: #c5a059;
            box-shadow: 0 0 10px rgba(197, 160, 89, 0.5);
            color: white;
            outline: none;
        }

        .custom-select {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 10px;
            padding: 12px 15px;
            width: 100%;
            appearance: none;
            transition: all 0.3s ease;
        }

        .custom-select option {
            background: #111;
            color: #fff;
        }

        .custom-select:focus {
            background: rgba(0, 0, 0, 0.5);
            border-color: #c5a059;
            box-shadow: 0 0 10px rgba(197, 160, 89, 0.5);
            color: white;
            outline: none;
        }

        .section-title {
            text-align: center;
            color: #f8f9fa;
            font-weight: 700;
            font-size: 1.25rem;
            margin: 30px 0 15px;
        }

        .divider {
            border: none;
            height: 1px;
            background: rgba(255, 255, 255, 0.15);
            margin: 20px 0;
        }

        .btn-login {
            background: linear-gradient(90deg, #c5a059 0%, #8a6c38 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            padding: 12px;
            width: 100%;
            margin-top: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            font-size: 1.1rem;
            font-weight: 700;
            padding: 12px;
            width: 100%;
            margin-top: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .btn-login:hover {
            background: linear-gradient(90deg, #d8b26a 0%, #9e7d43 100%);
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(197, 160, 89, 0.4);
            color: white;
        }

        .alert-custom {
            background: rgba(220, 53, 69, 0.8);
            backdrop-filter: blur(5px);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="overlay"></div>

    <div class="login-wrapper">
        <div class="glass-card">
            
            <div class="logo-container">
                <img src="assets/img/dalulisto.png" alt="Logo DALU">
            </div>
            
            <h1 class="title">Recuperar contraseña</h1>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div><?= htmlspecialchars($error); ?></div>
                </div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form action="?c=recoverpass&accion=recovery" method="POST">
                <div class="form-group mb-4">
                    <label for="usuario_recuperacion">Usuario</label>
                    <input type="text" name="usuario_recuperacion" class="form-control custom-input" id="usuario_recuperacion" placeholder="Ingrese su usuario" required>
                </div>

                <div class="form-group mb-4">
                    <label for="pregunta_seguridad">Pregunta de seguridad</label>
                    <select name="pregunta_seguridad" id="pregunta_seguridad" class="custom-select" required>
                        <option value="" disabled selected>Seleccione su pregunta</option>
                        <?php foreach ($preguntas_seguridad as $pregunta): ?>
                            <option value="<?= $pregunta['id'] ?>"><?= htmlspecialchars($pregunta['pregunta']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label for="respuesta_seguridad">Respuesta</label>
                    <input type="text" name="respuesta_seguridad" class="form-control custom-input" id="respuesta_seguridad" placeholder="Ingrese la respuesta" required>
                </div>

                <div class="form-group mb-4">
                    <label for="nueva_clave">Nueva contraseña</label>
                    <input type="password" name="nueva_clave" class="form-control custom-input" id="nueva_clave" placeholder="Ingrese la nueva contraseña" required>
                </div>

                <div class="form-group mb-4">
                    <label for="confirmar_clave">Confirmar contraseña</label>
                    <input type="password" name="confirmar_clave" class="form-control custom-input" id="confirmar_clave" placeholder="Repita la nueva contraseña" required>
                </div>

                <div class="mb-3">
                    <a href="?c=login&accion=view" class="text-decoration-none" style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">Inicia sesión con tu contraseña</a>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn-login">RECUPERAR</button>
                </div>
            </form>

        </div>
    </div>

</body>

</html>