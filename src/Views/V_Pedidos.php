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
    <script src="assets/js/citas.js" defer></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/DataTablet/datatables.min.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>
    <title>Pedidos</title>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>
        <div>
            <h1 class="titulo text-black">Pedidos</h1>
        </div>



        <div class="table-body">
            <nav class="navbar navbar-expand-lg d-flex justify-content-between p-3">
                <div class="col-4 col-md-4">
                    <button type="button" class="btn btn-light d-flex align-items-center" data-bs-toggle="modal"
                        data-bs-target="#citaModal" onclick="prepararModal('crear')">
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
                        <th scope="col">Cédula</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Doctor</th>
                        <th scope="col">Especialidad</th>
                        <th scope="col">Motivo</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($Pedidos as $pedidos): ?>
                        <tr>
                            <!-- <td><?php echo htmlspecialchars($cliente['Tipo_Cedula']); ?>-<?php echo htmlspecialchars($cliente['Cedula']); ?></td> -->
                            <td>
                                <?php
                                echo $cliente['Tipo_Cedula'] . "-";
                                $cedula = $cliente['Cedula'];
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
                            <td><?php echo htmlspecialchars($cita['Nombre']); ?></td>
                            <td><?php echo htmlspecialchars($cita['Telefono']); ?></td>
                            <td><?php echo htmlspecialchars($cita['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($cita['Doctor']); ?></td> <!-- Mostrar el nombre del doctor -->
                            <td><?php echo htmlspecialchars($cita['Especialidad']); ?></td> <!-- Mostrar la especialidad del doctor -->
                            <td><?php echo htmlspecialchars($cita['motivo']); ?></td>

                            <td class="text-center">
                                <button type="button" class="btn btn-warning m-1 btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#citaModal" onclick="prepararModal('editar', <?php echo htmlspecialchars(json_encode($cita)); ?>)">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                        <path fill="#000000" d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 125.7-86.8 86.8c-10.3 10.3-17.5 23.1-21 37.2l-18.7 74.9c-2.3 9.2-1.8 18.8 1.3 27.5L64 512c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zM549.8 235.7l14.4 14.4c15.6 15.6 15.6 40.9 0 56.6l-29.4 29.4-71-71 29.4-29.4c15.6-15.6 40.9-15.6 56.6 0zM311.9 417L441.1 287.8l71 71L382.9 487.9c-4.1 4.1-9.2 7-14.9 8.4l-60.1 15c-5.5 1.4-11.2-.2-15.2-4.2s-5.6-9.7-4.2-15.2l15-60.1c1.4-5.6 4.3-10.8 8.4-14.9z" />
                                    </svg>
                                </button>

                                <form action="index.php?c=citas&a=EliminarCita" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($cita['id']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de que deseas cambiar el estado de esta cita?');">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                            <path fill="#000000" d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </main>




    <div class="modal fade" id="citaModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="citaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="citaModalLabel">Registrar Pedido</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" method="POST" action="index.php?c=citas&a=GuardarCitas" id="citaForm">
                        <input type="hidden" name="id" id="citaId" value="">
                        <input type="hidden" name="id_servicio_medico" id="id_servicio_medico" value="">
                        <div class="container container-form">

                            <div class="mb-3" id="buscarcliente">
                                <label for="busqueda_Cliente" class="form-label">Buscar Cliente</label>
                                <input type="text" name="busqueda_paciente" class="form-control" id="busqueda_paciente" placeholder="Buscar Cliente" autocomplete="off" required />
                                <input type="hidden" name="id_paciente" id="id_paciente" value="">
                                <ul id="lista_pacientes" style="display: none; position: absolute; width: 90%;  z-index: 1000; background: white; text-align: center; border: 1px solid #ccc; padding: 0; margin-top: 2px;"></ul>
                            </div>

                            <div class="mb-3" id="seleccionarEspecialidad">
                                <label for="especialidad" class="form-label">Categoria</label>
                                <select id="especialidad" class="form-select" name="especialidad" onchange="cargarDoctoresPorEspecialidad()" required>
                                    <option value="">Seleccione una Categoria</option>
                                    <?php foreach ($especialidades as $esp): ?>
                                        <option value="<?= $esp['id'] ?>"><?= htmlspecialchars($esp['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3" id="buscarDireccion">
                                <label for="busqueda_direccion" class="form-label">Buscar direccion</label>
                                <input type="text" class="form-control" id="busqueda_direccion" placeholder="Buscar Direccion" autocomplete="off" required />
                                <ul id="lista_direccion" style="display: none; position: absolute; width: 90%; text-align: center; z-index: 1000; background: white; border: 1px solid #ccc;  padding: 0; margin-top: 2px;"></ul>

                                <!-- Div para mostrar el horario -->
                                <div id="horario_display" style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px; display: none;">
                                    <h6>Horario del Doctor:</h6>
                                    <div id="horario_content"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha</label>
                                <input type="date" name="fecha" id="fecha" class="form-control" required />
                            </div>

                           

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</body>

</html>