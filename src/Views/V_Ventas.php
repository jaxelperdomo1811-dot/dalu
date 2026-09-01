<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/css.css">
    <link rel="stylesheet" href="assets/css/tabla.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="icon" href="assets/img/dalulisto.png">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="assets/DataTablet/datatables.css">
    <script src="assets/js/Ventas.js" defer></script>
    <script src="assets/js/js.js" defer></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/DataTablet/datatables.min.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>

    <title>Ventas</title>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>
        <div>
            <h1 class="titulo text-black">Ventas</h1>
        </div>

        <div class="table-body">
            <nav class="navbar navbar-expand-lg d-flex justify-content-between p-3">
                <div class="col-4 col-md-4">
                    <button type="button" class="btn btn-white d-flex align-items-center" data-bs-toggle="modal"
                        data-bs-target="#pacienteModal" onclick="prepararModal('crear')">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="me-1" style="width: 1.5em; height: 1.5em;">
                            <path fill="blue" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                        </svg>
                        Registrar
                    </button>
                </div>

                <button type="button" class="btn btn-light btn-sm ver-entradas" data-id=entradasInsumoModal data-bs-toggle="modal" data-bs-target="#entradasInsumoModal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 1.5em; height: 1.5em;">
                        <path fill="blue" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                    </svg>
                    Ver Ventas
                </button>

            </nav>

            <div class="table-responsive">
                <table class="table" id="myTable">
                    <thead>
                        <tr>

                            <th scope="col-3">Nombre</th>
                            <th scope="col-3">Prendas</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">costo</th>
                            <th scope="col">Talla</th>
                            <th scope="col">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventas as $ventas) :
                        ?>
                            <tr>

                                <td><?php echo $ventas['nombre']; ?></td>
                                <td><?php echo $ventas['rif']; ?></td>
                                <td><?php echo $ventas['telefono']; ?></td>
                                <td><?php echo $ventas['email']; ?></td>
                                <td><?php echo $ventas['direccion']; ?></td>

                                <td class="">
                                    <button type="button" class="btn btn-warning m-1 btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#pacienteModal" onclick="prepararModal('editar', <?php echo htmlspecialchars(json_encode($proveedor)); ?>)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                            <path fill="#000000" d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 125.7-86.8 86.8c-10.3 10.3-17.5 23.1-21 37.2l-18.7 74.9c-2.3 9.2-1.8 18.8 1.3 27.5L64 512c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zM549.8 235.7l14.4 14.4c15.6 15.6 15.6 40.9 0 56.6l-29.4 29.4-71-71 29.4-29.4c15.6-15.6 40.9-15.6 56.6 0zM311.9 417L441.1 287.8l71 71L382.9 487.9c-4.1 4.1-9.2 7-14.9 8.4l-60.1 15c-5.5 1.4-11.2-.2-15.2-4.2s-5.6-9.7-4.2-15.2l15-60.1c1.4-5.6 4.3-10.8 8.4-14.9z" />
                                        </svg>
                                    </button>

                                    <form action="index.php?c=ventas&a=EliminarVentas" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $ventas['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta Venta?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                <path fill="#000000" d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
                                            </svg>
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-info m-1 btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#entradaModal" onclick="setProveedorId(<?php echo $proveedor['id']; ?>, '<?php echo addslashes($proveedor['nombre']); ?>')">
                                        <b>Registrar Entrada</b>
                                    </button>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- formulario de registro de entradas -->
    <div class="modal fade" id="entradaModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="entradaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="entradaModalLabel">Registrar Entrada</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" method="POST" action="?c=compraProductos&accion=insert" id="entradaForm">
                        <div class="container container-form">

                            <input type="hidden" name="id_proveedor" id="id_proveedor" value="">

                            <div class="mb-3">
                                <label for="nombre_proveedor" class="form-label">Proveedor</label>
                                <input type="text" class="form-control" name="nombre_proveedor" id="nombre_proveedor" placeholder="Nombre del Proveedor" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="numero_de_lote" class="form-label">Número de Lote</label>
                                <input type="text" class="form-control" name="numero_de_lote" id="numero_de_lote" placeholder="Número de Lote" required />
                            </div>

                            <div class="mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                <input type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso" value="<?= date('Y-m-d') ?>" required />
                            </div>

                            <div class="mb-3">
                                <label for="precio_compra" class="form-label">Precio de Compra</label>
                                <input type="number" step="0.01" class="form-control" name="precio_compra" id="precio_compra" placeholder="Precio de Compra" required />
                            </div>

                            
                            </div>

                            <div class="mb-3">
                                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                <input type="date" class="form-control" name="fecha_vencimiento" id="fecha_vencimiento" required />
                            </div>

                            <div class="mb-3">
                                <label for="cantidad_entrante" class="form-label">Cantidad Entrante</label>
                                <input type="number" class="form-control" name="cantidad_entrante" id="cantidad_entrante" placeholder="Cantidad Entrante" required />
                            </div>


                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
                            <button type='submit' class='btn btn-primary'>Registrar Entrada</button>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-success' data-bs-dismiss='modal'>Cerrar</button>
                            
                            <button type='submit' class='btn btn-danger'>Ingresar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="pacienteModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="pacienteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="pacienteModalLabel">Crear Venta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" method="POST" action="index.php?c=proveedores&a=GuardarProveedores" id="pacienteForm">
                        <input type="hidden" name="id" id="proveedorId" value="">
                        <div class="container container-form">

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" pminlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="nombre" id="nombre" placeholder="Nombre" required />
                            </div>

                            <div class="mb-3">
                                <label for="apellido" class="form-label">Prenda</label>
                                <input type="text" minlength="5" maxlength="15" title="Ingrese solo texto" name="rif" class="form-control" id="rif" placeholder="Prendas" required />
                            </div>

                            <div class='mb-3'>
                                <label for='cantidad' class='form-label'>Cantidad</label>
                                <input type='text' minlength="1" maxlength="15" title="Ingrese solo texto" name="rif" class="form-control" id="rif" placeholder="Cantidad" required/>
                            </div>


                            <div class='mb-3'>
                                <label for='Costo' class='form-label'>Costo</label>
                                <input type='text'  minlength="5" maxlength="25" name='Costo' class='form-control' id='direccion' title="entre 5 y 25 caracteres" placeholder="Costo" required />
                            </div>

                            <div class='mb-3'>
                                <label for='talla' class='form-label'>Talla</label>
                                <input type='text' minlength="1" maxlength="5" name='talla' class='form-control' id='talla' title="entre 5 y 25 caracteres" placeholder="Talla" required />
                            </div>


                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
                            <button type='submit' name='btnCrear' value='crear' class='btn btn-danger'>Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


   
    <div class="modal fade" id="entradasInsumoModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="entradasInsumoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl text-center">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenidoEntradasInsumo">
                   
                </div>
            </div>
        </div>
    </div>



</body>

</html>