<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fechainicio'];
    $fecha_fin = $_POST['fechafin'] ?? null; // Asigna null si no se proporciona

    // Asegúrate de que si fecha_fin es null, no se incluya en la consulta
    if ($fecha_fin) {
        $sql = "INSERT INTO molienda (descripcion, fecha_inicio, fecha_fin) VALUES ('$descripcion', '$fecha_inicio', '$fecha_fin')";
    } else {
        $sql = "INSERT INTO molienda (descripcion, fecha_inicio) VALUES ('$descripcion', '$fecha_inicio')";
    }

    if (mysqli_query($conexion, $sql)) {
        // Obtener el ID de la molienda recién creada
        $id_molienda = mysqli_insert_id($conexion);
        
        // Redirigir a la página de molienda con el ID de la molienda
        header("Location: molienda.php?id_molienda=$id_molienda");
        exit();
    } else {
        echo "Error al crear la molienda: " . mysqli_error($conexion);
    }

    mysqli_close($conexion);
}
?>
