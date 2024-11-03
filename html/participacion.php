<?php
include 'conexion.php'; // Incluir la conexión a la base de datos

// Obtener el ID de la molienda desde la URL
$id_molienda = $_GET['id_molienda'] ?? null;

// Consulta para obtener los datos de la molienda
$sql_molienda = "SELECT * FROM molienda WHERE id_molienda = $id_molienda";
$resultado_molienda = mysqli_query($conexion, $sql_molienda);
$molienda = mysqli_fetch_assoc($resultado_molienda);

// Consulta para obtener las participaciones
$sql_participaciones = "SELECT p.id_participacion, per.nombre AS persona, l.nombre_labor AS labor, 
    p.cantidad_fondadas, p.fecha_participacion, p.monto_total
    FROM participacion p
    JOIN persona per ON p.id_persona = per.id_persona
    JOIN labor l ON p.id_labor = l.id_labor
    WHERE p.id_molienda = $id_molienda";
$resultado_participaciones = mysqli_query($conexion, $sql_participaciones);

// Consulta para obtener las personas
$sql_personas = "SELECT id_persona, nombre FROM persona";
$resultado_personas = mysqli_query($conexion, $sql_personas);

// Consulta para obtener las labores
$sql_labores = "SELECT id_labor, nombre_labor FROM labor";
$resultado_labores = mysqli_query($conexion, $sql_labores);

// Consulta para obtener el total de fondadas de la molienda
$sql_total_fondadas = "SELECT COUNT(*) AS total_fondadas FROM fondada WHERE id_molienda = $id_molienda";
$resultado_fondadas = mysqli_query($conexion, $sql_total_fondadas);
$total_fondadas = mysqli_fetch_assoc($resultado_fondadas)['total_fondadas'];

// Verificar si hay un mensaje de éxito o error
$mensaje = $_GET['mensaje'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participaciones en Molienda</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Participaciones para la Molienda: <?php echo htmlspecialchars($molienda['descripcion']); ?></h2>

    <!-- Mostrar mensajes de éxito o error -->
    <?php if ($mensaje): ?>
        <div id="mensaje-exito" class="alert alert-success">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <!-- Menú de pestañas -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="crear-participacion-tab" data-toggle="tab" href="#crear-participacion" role="tab" aria-controls="crear-participacion" aria-selected="true">Crear Participación</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="lista-participaciones-tab" data-toggle="tab" href="#lista-participaciones" role="tab" aria-controls="lista-participaciones" aria-selected="false">Lista de Participaciones</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <!-- Pestaña de Crear Participación -->
        <div class="tab-pane fade show active" id="crear-participacion" role="tabpanel" aria-labelledby="crear-participacion-tab">
            <div class="card mt-4">
                <div class="card-header">Agregar Participación</div>
                <div class="card-body">
                    <form action="registroparticipacion.php" method="POST">
                        <input type="hidden" name="id_molienda" value="<?php echo $id_molienda; ?>">

                        <div class="form-group">
                            <label for="id_persona">Persona</label>
                            <select name="id_persona" class="form-control" required>
                                <option value="">Seleccione una persona</option>
                                <?php while ($persona = mysqli_fetch_assoc($resultado_personas)): ?>
                                    <option value="<?php echo $persona['id_persona']; ?>"><?php echo htmlspecialchars($persona['nombre']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_labor">Labor</label>
                            <select name="id_labor" class="form-control" required>
                                <option value="">Seleccione una labor</option>
                                <?php while ($labor = mysqli_fetch_assoc($resultado_labores)): ?>
                                    <option value="<?php echo $labor['id_labor']; ?>"><?php echo htmlspecialchars($labor['nombre_labor']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cantidad_fondadas">Cantidad de Fondadas</label>
                            <input type="number" name="cantidad_fondadas" class="form-control" max="<?php echo $total_fondadas; ?>" required>
                            <small class="form-text text-muted">Fondadas de esta molienda: <?php echo $total_fondadas; ?></small>
                        </div>

                        <div class="form-group">
                            <label for="fechaparticipacion">Fecha de Participación</label>
                            <input type="datetime-local" name="fechaparticipacion" class="form-control" required>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" name="procesamiento" id="procesamiento" class="form-check-input">
                            <label for="procesamiento" class="form-check-label">Es procesamiento</label>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Agregar Participación</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Pestaña de Lista de Participaciones -->
        <div class="tab-pane fade" id="lista-participaciones" role="tabpanel" aria-labelledby="lista-participaciones-tab">
            <div class="card mt-4">
                <div class="card-header">
                    Lista de Participaciones
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Persona</th>
                                <th>Labor</th>
                                <th>Cantidad de Fondadas</th>
                                <th>Fecha de Participación</th>
                                <th>Monto Total</th> <!-- Nueva columna -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($participacion = mysqli_fetch_assoc($resultado_participaciones)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($participacion['persona']); ?></td>
                                    <td><?php echo htmlspecialchars($participacion['labor']); ?></td>
                                    <td><?php echo htmlspecialchars($participacion['cantidad_fondadas']); ?></td>
                                    <td><?php echo htmlspecialchars($participacion['fecha_participacion']); ?></td>
                                    <td><?php echo htmlspecialchars($participacion['monto_total']); ?></td> <!-- Mostrar el monto total -->
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
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
