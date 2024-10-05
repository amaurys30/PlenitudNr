<?php
include 'conexion.php';

// Verifica si se pasó el ID de molienda
if (isset($_GET['id_molienda'])) {
    $id_molienda = $_GET['id_molienda'];

    // Realiza la consulta para obtener la molienda
    $sql = "SELECT * FROM molienda WHERE id_molienda = $id_molienda";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado) {
        $molienda = mysqli_fetch_assoc($resultado);
    } else {
        echo "Error al obtener los datos de la molienda: " . mysqli_error($conexion);
    }
} else {
    echo "ID de molienda no proporcionado.";
    exit();
}

// Configuración para la paginación
$fondadas_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual > 1) ? ($pagina_actual * $fondadas_por_pagina) - $fondadas_por_pagina : 0;

// Consulta para obtener las fondadas de esta molienda, con paginación y orden por fecha
$sql_fondadas = "SELECT f.id_fondada, f.fecha_agregada, f.cantidad_litros,
                    (SELECT SUM(pp.cantidad_panela) FROM produccionpanela pp WHERE pp.id_fondada = f.id_fondada AND pp.tipo_panela = 'grande') AS cantidad_grande,
                    (SELECT SUM(pp.cantidad_panela) FROM produccionpanela pp WHERE pp.id_fondada = f.id_fondada AND pp.tipo_panela = 'mediana') AS cantidad_mediana,
                    (SELECT SUM(pp.cantidad_panela) FROM produccionpanela pp WHERE pp.id_fondada = f.id_fondada AND pp.tipo_panela = 'pequeña') AS cantidad_pequena
                FROM fondada f 
                WHERE f.id_molienda = $id_molienda
                ORDER BY f.fecha_agregada DESC
                LIMIT $inicio, $fondadas_por_pagina";
$resultado_fondadas = mysqli_query($conexion, $sql_fondadas);

// Consulta para contar el número total de fondadas
$sql_conteo = "SELECT COUNT(*) AS total_fondadas FROM fondada WHERE id_molienda = $id_molienda";
$resultado_conteo = mysqli_query($conexion, $sql_conteo);
$total_fondadas = mysqli_fetch_assoc($resultado_conteo)['total_fondadas'];

// Calcula el total de páginas
$total_paginas = ceil($total_fondadas / $fondadas_por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Molienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h3>Detalles de la Molienda</h3>
        <p>
            <strong>Descripción:</strong> <?php echo htmlspecialchars($molienda['descripcion'] ?? 'No disponible'); ?><br>
            <strong>Fecha de Inicio:</strong> <?php echo htmlspecialchars($molienda['fecha_inicio'] ?? 'No disponible'); ?><br>
            <strong>Fecha de Fin:</strong> <?php echo htmlspecialchars($molienda['fecha_fin'] ?? 'No disponible'); ?><br>
        </p>

        <a href="index.php" class="btn btn-success">Volver</a>

        <!-- Botón para abrir el modal de agregar fondada -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarFondadaModal">Agregar Fondada</button>
        <!-- boton para participaciones -->
        <a href="participacion.php?id_molienda=<?php echo $id_molienda; ?>" class="btn btn-primary">Participaciones</a>
        <!-- boton para pagos -->
        <a href="pagos.php?id_molienda=<?php echo $id_molienda; ?>" class="btn btn-primary">Pagos</a>
        <!-- Tabla de fondadas -->
        <h4 class="mt-4">Fondadas Registradas</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cantidad de Litros</th>
                    <th>Panela Grande</th>
                    <th>Panela Mediana</th>
                    <th>Panela Pequeña</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fondada = mysqli_fetch_assoc($resultado_fondadas)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fondada['fecha_agregada']); ?></td>
                        <td><?php echo htmlspecialchars($fondada['cantidad_litros']); ?></td>
                        <td><?php echo htmlspecialchars($fondada['cantidad_grande'] ?? 0); ?></td>
                        <td><?php echo htmlspecialchars($fondada['cantidad_mediana'] ?? 0); ?></td>
                        <td><?php echo htmlspecialchars($fondada['cantidad_pequena'] ?? 0); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>">
                        <a class="page-link" href="?id_molienda=<?php echo $id_molienda; ?>&pagina=<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <!-- Modal de agregar fondada -->
    <div class="modal fade" id="agregarFondadaModal" tabindex="-1" aria-labelledby="fondadaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fondadaModalLabel">Agregar Fondada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="registrofondadayproduccion.php" method="POST">
                        <input type="hidden" name="id_molienda" value="<?php echo $id_molienda; ?>">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="cantidad_litros" name="cantidad_litros" step="0.01" value="100.00" required>
                            <label for="cantidad_litros">Cantidad de Litros</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="cantidad_grande" name="cantidad_grande" required>
                            <label for="cantidad_grande">Cantidad de Panela Grande</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="cantidad_mediana" name="cantidad_mediana" required>
                            <label for="cantidad_mediana">Cantidad de Panela Mediana</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="cantidad_pequena" name="cantidad_pequena" required>
                            <label for="cantidad_pequena">Cantidad de Panela Pequeña</label>
                        </div>
                        <input type="submit" value="Agregar Fondada" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript necesario para los modales de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
