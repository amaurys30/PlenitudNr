<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    // Si no está autenticado, redirigir al inicio de sesión
    header("Location: ../index.php");
    exit;
}
?>

<?php
include 'conexion.php'; // Incluir la conexión a la base de datos

// Obtener el ID de la molienda desde la URL
$id_molienda = $_GET['id_molienda'] ?? null;

// Consulta para obtener los datos de la molienda
$sql_molienda = "SELECT * FROM molienda WHERE id_molienda = $id_molienda";
$resultado_molienda = mysqli_query($conexion, $sql_molienda);
$molienda = mysqli_fetch_assoc($resultado_molienda);

// Consulta para obtener los gastos de la molienda
$sql_gastos = "SELECT * FROM gastos WHERE id_molienda = $id_molienda";
$resultado_gastos = mysqli_query($conexion, $sql_gastos);

// Consulta para obtener el total de los gastos de la molienda
$sql_total_gastos = "SELECT SUM(monto) AS total_gastos FROM gastos WHERE id_molienda = $id_molienda";
$resultado_total_gastos = mysqli_query($conexion, $sql_total_gastos);
$total_gastos = mysqli_fetch_assoc($resultado_total_gastos)['total_gastos'];


// Verificar si hay un mensaje de éxito o error
$mensaje = $_GET['mensaje'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gastos de Molienda</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Gastos para la Molienda: <?php echo htmlspecialchars($molienda['descripcion']); ?></h2>

    <!-- Mostrar mensajes de éxito o error -->
    <?php if ($mensaje): ?>
        <div id="mensaje-exito" class="alert alert-success">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <!-- Menú de pestañas -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="crear-gasto-tab" data-toggle="tab" href="#crear-gasto" role="tab" aria-controls="crear-gasto" aria-selected="true">Agregar Gasto</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="lista-gastos-tab" data-toggle="tab" href="#lista-gastos" role="tab" aria-controls="lista-gastos" aria-selected="false">Lista de Gastos</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <!-- Pestaña de Agregar Gasto -->
        <div class="tab-pane fade show active" id="crear-gasto" role="tabpanel" aria-labelledby="crear-gasto-tab">
            <div class="card mt-4">
                <div class="card-header">Agregar Gasto</div>
                <div class="card-body">
                    <form action="registrogasto.php" method="POST">
                        <input type="hidden" name="id_molienda" value="<?php echo $id_molienda; ?>">

                        <div class="form-group">
                            <label for="nombre">Nombre del Gasto</label>
                            <input type="text" name="nombre" class="form-control" list="opcionesGasto" required>
                            <datalist id="opcionesGasto">
                                <option value="ACPM">
                                <option value="Gasolina">
                                <option value="Aceite">
                                <option value="Jabón">
                                <option value="Limpido">
                            </datalist>
                        </div>


                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="text" name="cantidad" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="monto">Monto total del gasto</label>
                            <input type="number" name="monto" class="form-control" step="0.01" required>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Agregar Gasto</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Pestaña de Lista de Gastos -->
        <div class="tab-pane fade" id="lista-gastos" role="tabpanel" aria-labelledby="lista-gastos-tab">
            <div class="card mt-4">
                <div class="card-header">
                    Lista de Gastos
                </div>
                <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Monto</th>
                            <th>Fecha de Gasto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($gasto = mysqli_fetch_assoc($resultado_gastos)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($gasto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($gasto['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($gasto['cantidad']); ?></td>
                                <td><?php echo htmlspecialchars(number_format($gasto['monto'], 2)); ?></td>
                                <td><?php echo htmlspecialchars($gasto['fecha_gasto']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td><strong>Total:</strong> <?php echo htmlspecialchars(number_format($total_gastos ?? 0, 2)); ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                </div>
            </div>
        </div>
    </div>

    <a href="molienda.php?id_molienda=<?php echo $id_molienda; ?>" class="btn btn-success mt-4">Volver</a>
</div>
<script src="../js/utilidades.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
