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
    </div>
</body>
</html>
