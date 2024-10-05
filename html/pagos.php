<?php
include 'conexion.php'; // Conexión a la base de datos

$id_molienda = $_GET['id_molienda'];

// Consulta para obtener los pagos pendientes (participaciones no pagadas)
$sql_pendientes = "SELECT 
                    pa.id_participacion, 
                    per.nombre AS nombre_persona, 
                    l.nombre_labor, 
                    pa.cantidad_fondadas, 
                    (pa.cantidad_fondadas * l.precio_por_fondada) AS total_por_participacion
                   FROM participacion pa
                   JOIN persona per ON pa.id_persona = per.id_persona
                   JOIN labor l ON pa.id_labor = l.id_labor
                   LEFT JOIN pago p ON pa.id_participacion = p.id_participacion
                   WHERE p.id_participacion IS NULL AND pa.id_molienda = $id_molienda";

$resultado_pendientes = mysqli_query($conexion, $sql_pendientes);

// Consulta para obtener los pagos realizados
$sql_realizados = "SELECT 
                    pa.id_participacion, 
                    per.nombre AS nombre_persona, 
                    l.nombre_labor, 
                    pa.cantidad_fondadas, 
                    (pa.cantidad_fondadas * l.precio_por_fondada) AS total_por_participacion, 
                    p.fecha_pago
                   FROM pago p
                   JOIN participacion pa ON p.id_participacion = pa.id_participacion
                   JOIN persona per ON pa.id_persona = per.id_persona
                   JOIN labor l ON pa.id_labor = l.id_labor
                   WHERE pa.id_molienda = $id_molienda";

$resultado_realizados = mysqli_query($conexion, $sql_realizados);

// Función para registrar el pago
if (isset($_POST['pagar'])) {
    $id_participacion = $_POST['id_participacion'];
    $monto_total = $_POST['monto_total'];
    
    // Insertar el pago en la tabla 'pago' y registrar la fecha del pago automáticamente
    $sql_registrar_pago = "INSERT INTO pago (id_participacion, monto_total) VALUES ($id_participacion, $monto_total)";
    mysqli_query($conexion, $sql_registrar_pago);
    
    // Redirigir a la página de pagos después de realizar el pago
    header("Location: pagos.php?id_molienda=$id_molienda");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos - Molienda <?php echo $id_molienda; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Pagos - Molienda <?php echo $id_molienda; ?></h3>
    
    <!-- Menú de opciones -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes" type="button" role="tab" aria-controls="pendientes" aria-selected="true">Pagos Pendientes</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="realizados-tab" data-bs-toggle="tab" data-bs-target="#realizados" type="button" role="tab" aria-controls="realizados" aria-selected="false">Pagos Realizados</button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <!-- Pagos Pendientes -->
        <div class="tab-pane fade show active" id="pendientes" role="tabpanel" aria-labelledby="pendientes-tab">
            <div class="card mt-4">
                <div class="card-header">Lista de Pagos Pendientes</div>
                <div class="card-body">
                    <?php if (mysqli_num_rows($resultado_pendientes) > 0): ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Persona</th>
                                    <th>Labor</th>
                                    <th>Cantidad Fondadas</th>
                                    <th>Total</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($pendiente = mysqli_fetch_assoc($resultado_pendientes)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($pendiente['nombre_persona']); ?></td>
                                        <td><?php echo htmlspecialchars($pendiente['nombre_labor']); ?></td>
                                        <td><?php echo htmlspecialchars($pendiente['cantidad_fondadas']); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($pendiente['total_por_participacion'], 2)); ?></td>
                                        <td>
                                            <form method="post" onsubmit="return confirm('¿Está seguro de que desea realizar este pago?');">
                                                <input type="hidden" name="id_participacion" value="<?php echo $pendiente['id_participacion']; ?>">
                                                <input type="hidden" name="monto_total" value="<?php echo $pendiente['total_por_participacion']; ?>">
                                                <button type="submit" name="pagar" class="btn btn-success">Pagar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No hay pagos pendientes.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pagos Realizados -->
        <div class="tab-pane fade" id="realizados" role="tabpanel" aria-labelledby="realizados-tab">
            <div class="card mt-4">
                <div class="card-header">Lista de Pagos Realizados</div>
                <div class="card-body">
                    <?php if (mysqli_num_rows($resultado_realizados) > 0): ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Persona</th>
                                    <th>Labor</th>
                                    <th>Cantidad Fondadas</th>
                                    <th>Total</th>
                                    <th>Fecha Pago</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($realizado = mysqli_fetch_assoc($resultado_realizados)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($realizado['nombre_persona']); ?></td>
                                        <td><?php echo htmlspecialchars($realizado['nombre_labor']); ?></td>
                                        <td><?php echo htmlspecialchars($realizado['cantidad_fondadas']); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($realizado['total_por_participacion'], 2)); ?></td>
                                        <td><?php echo htmlspecialchars($realizado['fecha_pago']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No hay pagos realizados.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <a href="molienda.php?id_molienda=<?php echo $id_molienda; ?>" class="btn btn-primary mt-4">Volver a Molienda</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
