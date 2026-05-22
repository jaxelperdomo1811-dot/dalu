<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Ingreso</title>
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <style>
        /* Ajusta el padding del contenedor principal si es necesario */
        .container {
            padding-top: 5px;
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Para el formulario, usa un max-width y centra en pantallas pequeñas */
        .form-container {
            margin-top: 100px;
            max-width: 600px;
            width: 100%;
            /* Hace que ocupe toda la anchura disponible en pantallas pequeñas */
            margin-left: auto;
            /* Centra horizontalmente */
            margin-right: auto;
        }

        .image-container {
            max-width: 100px;
        }

        .custom-input {
            border: none;
            /* Sin bordes */
            border-bottom: 20px solid ;
            /* Borde inferior gris */
            border-radius: 0;
            /* Sin bordes redondeados */
            box-shadow: none;
            /* Sin sombra */
            outline: none;
            /* Sin contorno al hacer clic */
        }

        .custom-input:focus {
            border-bottom: 2px solid blue;
            /* Cambia el color del borde inferior al enfocar */
            box-shadow: none;
            /* Sin sombra al enfocar */
        }

        .btn {
            background: solido red(90deg, #c5a059 0%, #494847 100%);
            border-radius: 20px;
            color: red;
            font-size: 15px;
            margin: 5px;
        }

        .a {
            color: white;
            font-size: 18px;
            text-decoration: none;
        }

        .header {
            display: flex;
            align-items: center;
            /* Centra verticalmente el contenido */
            justify-content: space-between;
            /* Espacio entre el logo y el título */
            padding: 10px;
            /* Espaciado interno */
            /* Color de fondo */
        }

        .logo {
            max-width: 200px;
            /* Ancho máximo del logo */
        }

        .title {
            flex-grow: 2;
            /* Permite que el título ocupe el espacio restante */
            text-align: center;
            /* Centra el texto */
            font-size: 3.3rem;
            /* Tamaño del texto */
            margin: 10px;
            /* Elimina el margen */
            color:rgb(255, 255, 255);
            font-family: Lucida Calligraphy;
        }

        .tittle2 {
            color:rgb(255, 255, 255);
            border-color:rgb(0, 0, 0);
            text-decoration: underline;
            font-family: Calibri (Cuerpo);
        }

        .fontlogin {
            background-size: 50%;
        }

        .btn {
            width: 100%;
        }
    </style>
</head>

<body class="fontlogin" style="background-image: url(assets/img/fondodalu.jpeg); background-size: covre; width: 100%; height: 100%;">


    <div class="header">
    <img src="assets/img/dalulisto.png" class="logo" alt="Logo">
        <h1 class="title">DALU</h1> <!-- Título -->
    </div>

    <div class="container">
        <div class="row">
            <div class="col-8">
                <div>

                </div>
            </div>

            <div class="col-4">
                <div class="form-container">
                    <form action="?c=login&accion=login" method="POST">

                        <!-- Mensaje de error -->
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <h1 class="text-center tittle2 mb-3">Ingresar</h1>
                        <div class="form-group">
                            <label for="usuario"> <strong> Usuario</strong></label>
                            <input type="text" name="usuario" class="form-control custom-input" id="usuario" placeholder="Ingrese su usuario"
                                style="border-radius: none; border-bottom-left-radius-: px; border-bottom-right-radius: 4px;" required>
                        </div>
                        <div class="form-group">
                            <label for="contraseña"> <strong>Contraseña</strong></label>
                            <input type="password" name="password" class="form-control custom-input" id="password" placeholder="Ingrese su contraseña"
                                style="border-radius: none; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;" required>
                        </div>

                        <div class="text-center m-2">
                            <button type="submit" class="btn btn-maggin fw-semibold">Ingresar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</body>

</html>