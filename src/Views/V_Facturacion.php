<!DOCTYPE html>
<html>

<head>
    <title>Clinica - Facturación</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/css/css.css">
    <link rel="stylesheet" href="assets/css/tabla.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <script src="assets/js/js.js" defer></script>
    <script src="assets/js/Facturacion.js" defer></script>

    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>
        <div>
            <h1 class="titulo">Facturación</h1>
        </div>
        <hr>

        <div class="container mt-5">
            <div class="card shadow-sm border-0 rounded-4" style="max-width: 800px; margin: 0 auto;">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-plus me-2"></i>Registrar Factura</h5>
                </div>

                <div class="card-body bg-light-subtle px-4 py-4">
                    <form id="facturaForm" action="index.php?c=facturacion&a=GuardarFacturaCompleta" method="POST">

                        <!-- Paciente -->
                        <div class="mb-4 position-relative">
                            <label for="paciente" class="form-label fw-semibold">Paciente</label>
                            <input type="text" id="paciente" name="paciente" class="form-control rounded-pill" autocomplete="off" placeholder="Ingrese nombre o cédula..." required>
                            <input type="hidden" id="id_paciente" name="id_paciente">
                            <div id="pacienteList" class="list-group position-absolute shadow-sm" style="z-index:1000; width: 100%; display:none;"></div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-6"><!-- Select de Doctores -->
                        <div class="mb-4">
                            <label for="selectDoctor" class="form-label fw-semibold">Doctor</label>
                            <select id="selectDoctor" name="id_doctor" class="form-select rounded-pill" required>
                                <option value="">-- Selecciona un doctor --</option>
                                <?php foreach ($doctores as $doctor): ?>
                                    <option value="<?= htmlspecialchars($doctor['id']) ?>"><?= htmlspecialchars($doctor['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> </div>

                                <div class="col-md-6"> <!-- Servicio Médico -->
                        <div class="mb-4">
                            <label for="selectServicio" class="form-label fw-semibold">Servicio Médico</label>
                            <select id="selectServicio" name="id_servicio_medico" class="form-select rounded-pill" disabled required>
                                <option value="">-- Primero selecciona un doctor --</option>
                                <!-- Opciones se cargan con JS -->
                            </select>
                        </div> </div>


                            </div>
                        </div>

                        
                       


                        <!-- Fecha -->
                        <div class="mb-4">
                            <label for="fecha" class="form-label fw-semibold">Fecha</label>
                            <input type="date" id="fecha" name="fecha" class="form-control rounded-pill" value="<?= date('Y-m-d') ?>" required>
                        </div>

                         <div class="container">
                            <div class="row">
                                <div class="col-md-6"> <!-- Método de Pago -->
                        <div class="mb-4">
                            <label for="id_metodo_pago" class="form-label fw-semibold">Método de Pago</label>
                            <select id="id_metodo_pago" name="id_metodo_pago" class="form-select rounded-pill" required>
                                <option value="">Seleccione un método</option>
                                <?php foreach ($pagos as $pago): ?>
                                    <option value="<?= $pago['id'] ?>"><?= htmlspecialchars($pago['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> </div>

                                <div class="col-md-6">   <!-- Referencia -->
                        <div class="mb-4">
                            <label for="referencia" class="form-label fw-semibold">Referencia</label>
                            <input type="text" id="referencia" name="referencia" class="form-control rounded-pill" placeholder="Número de referencia" required>
                        </div> </div>


                            </div>
                        </div>

                       

                     

                        <!-- Monto -->
                        <div class="mb-4">
                            <label for="monto_pago" class="form-label fw-semibold">Monto a Pagar</label>
                            <input type="number" step="0.01" id="monto_pago" name="monto_pago" class="form-control rounded-pill" required>
                        </div>

                        <!-- Botón -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary rounded-pill">
                                <i class="bi bi-save me-2"></i>Guardar Factura
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>


</body>

</html>