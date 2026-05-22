<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/css.css">
    <link rel="stylesheet" href="assets/css/tabla.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="stylesheet" href="assets/DataTablet/datatables.css">
    <script src="assets/js/js.js" defer></script>
    <script src="assets/js/Horario.js" defer></script>
    <script src="assets/js/doctores.js" defer></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/DataTablet/datatables.min.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>

    <title>Doctores</title>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>
        <div>
            <h1 class="titulo">Doctores</h1>
        </div>

        <div class="table-body">
            <nav class="navbar navbar-expand-lg d-flex justify-content-between p-3">
                <div class="col-4 col-md-4">
                    <button type="button" class="btn btn-light d-flex align-items-center" data-bs-toggle="modal"
                        data-bs-target="#pacienteModal" onclick="prepararModal('crear')">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="me-1" style="width: 1.5em; height: 1.5em;">
                            <path fill="blue" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                        </svg>
                        Registrar
                    </button>
                </div>
            </nav>

            <div class="table-responsive">

                <table class="table" id="myTable">
                    <thead>
                        <tr>

                            <th scope="col">Cedula</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellido</th>
                            <th scope="col">Telefono</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Direccion</th>
                            <th scope="col">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($personal as $doctor) :
                        ?>
                            <tr>

                                <td>
                                    <?php
                                    $cedula = $doctor['cedula'];
                                    // Asegúrate de que la cédula sea un número y tenga la longitud correcta
                                    if (is_numeric($cedula) && strlen($cedula) == 8) {
                                        // Formatear la cédula
                                        $formateada = substr($cedula, 0, 2) . '.' . substr($cedula, 2, 3) . '.' . substr($cedula, 5, 3);
                                        echo $formateada;
                                    } else {
                                        echo $cedula; // O manejar el error de otra manera
                                    }
                                    ?>
                                </td>
                                <td><?php echo $doctor['nombre']; ?></td>
                                <td><?php echo $doctor['apellido']; ?></td>
                                <td><?php echo $doctor['telefono']; ?></td>
                                <td><?php echo $doctor['correo']; ?></td>
                                <td><?php echo $doctor['direccion']; ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-warning m-1 btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#pacienteModal" onclick="prepararModal('editar', <?php echo htmlspecialchars(json_encode($doctor)); ?>)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                            <path fill="#000000" d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 125.7-86.8 86.8c-10.3 10.3-17.5 23.1-21 37.2l-18.7 74.9c-2.3 9.2-1.8 18.8 1.3 27.5L64 512c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zM549.8 235.7l14.4 14.4c15.6 15.6 15.6 40.9 0 56.6l-29.4 29.4-71-71 29.4-29.4c15.6-15.6 40.9-15.6 56.6 0zM311.9 417L441.1 287.8l71 71L382.9 487.9c-4.1 4.1-9.2 7-14.9 8.4l-60.1 15c-5.5 1.4-11.2-.2-15.2-4.2s-5.6-9.7-4.2-15.2l15-60.1c1.4-5.6 4.3-10.8 8.4-14.9z" />
                                        </svg>
                                    </button>

                                    <form action="index.php?c=personal&a=EliminarDoctor" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $doctor['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este Doctor?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                <path fill="#000000" d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
                                            </svg>
                                        </button>
                                    </form>
                                    <!-- Botón para agregar horario -->
                                    <button type="button" class="btn btn-info m-1 btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#registroModal" onclick="asignarDoctorId(<?php echo $doctor['id']; ?>)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                            <path fill="#000000" d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z" />
                                        </svg>
                                    </button>


                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>


<div class="modal fade" id="pacienteModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="pacienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="pacienteModalLabel">Crear Doctor</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="POST" action="index.php?c=personal&a=GestionarDoctor" id="doctorForm">
                    <input type="hidden" name="id" id="doctorId" value="">
                    <div class="container container-form">

                        <div class="mb-3 d-flex align-items-center gap-3">
                            <div style='flex: 2;'>
                                <label for='cedula' class='form-label'>Cédula</label>
                                <input type='text' class='form-control' pattern='[0-9]{6,8}' title="Solo números, entre 6 y 8 caracteres" name='cedula' id='cedula' placeholder='Número de Cédula' required />
                                <div id="mensaje-cedula" style="color: red; margin-top: 5px;"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" minlength="5" maxlength="15" pattern="[A-Za-z\s]{3,}" title="Solo letras y espacios, entre 5 y 15 caracteres" name="nombre" id="nombre" placeholder="Nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" minlength="5" maxlength="15" pattern="[A-Za-z\s]{3,}" title="Solo letras y espacios, entre 5 y 15 caracteres" name="apellido" id="apellido" placeholder="Apellido" required>
                        </div>
                        <div class='mb-3'>
                            <label for='numero_telefono' class='form-label'>Teléfono</label>
                            <input type='text' class='form-control' pattern='[0-9]{9,11}' title="ingrese solo numeros, entre 9 y 11 caracteres" name='telefono' id='numero_telefono' placeholder="teléfono" required>
                        </div>

                        <div class='mb-3'>
                            <label for='direccion' class='form-label'>Correo</label>
                            <input type='email' class='form-control' name='correo' id='correo' placeholder="@gmail.com" required>
                        </div>

                        <div class='mb-3'>
                            <label for='direccion' class='form-label'>Dirección</label>
                            <input type='text' class='form-control' minlength="5" maxlength="50" name='direccion' id='direccion' placeholder="Dirección" required>
                        </div>

                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
                        <button type='submit' name='btnCrear' value='crear' class='btn btn-primary' id="botton">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal para registrar días laborables y horarios -->
<div class="modal fade" id="registroModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="registroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="registroModalLabel">Registrar Días Laborables y Horario</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="registroForm">
                    <input type="hidden" name="id_personal" id="id_personal" value="">

                    <!-- Días Laborables -->
                    <div class="mb-3">
                        <label for="id_horario" class="form-label">Días Laborables del Doctor <small id="nombre"></small></label>
                        <select name="id_horario" id="id_horario" class="form-select" required>
                            <option value="">Cargando días laborables...</option>
                        </select>
                    </div>

                    <!-- Horario -->
                    <div class="mb-3">
                        <label for="hora_entrada" class="form-label">Hora de Entrada</label>
                        <input type="time" class="form-control" name="hora_entrada" id="hora_entrada" required>
                    </div>
                    <div class="mb-3">
                        <label for="hora_salida" class="form-label">Hora de Salida</label>
                        <input type="time" class="form-control" name="hora_salida" id="hora_salida" required>
                    </div>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
                <!-- Div para mostrar horarios existentes -->
                <div id="horario_display" style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px;">
                    <h6>Horarios Registrados:</h6>
                    <div id="horario_content"></div>
                </div>
            </div>
        </div>
    </div>
</div>