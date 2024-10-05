<?php
include 'conexion.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_molienda = $_POST['id_molienda'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fechainicio'];
    $fecha_fin = !empty($_POST['fechafin']) ? $_POST['fechafin'] : null;
    $estado = $_POST['estado'];

    // Sanitización de datos (si es necesario)
    $descripcion = mysqli_real_escape_string($conexion, $descripcion);

    // Actualizar la molienda en la base de datos
    if ($fecha_fin) {
        $sql = "UPDATE Molienda 
                SET descripcion = '$descripcion', fecha_inicio = '$fecha_inicio', fecha_fin = '$fecha_fin', estado = '$estado'
                WHERE id_molienda = $id_molienda";
    } else {
        $sql = "UPDATE Molienda 
                SET descripcion = '$descripcion', fecha_inicio = '$fecha_inicio', estado = '$estado'
                WHERE id_molienda = $id_molienda";
    }

    if (mysqli_query($conexion, $sql)) {
        // Si la actualización fue exitosa
        header("Location: index.php?actualizacion=Actualizacion exitosa");
    } else {
        // Si hubo un error en la actualización
        echo "Error: " . mysqli_error($conexion);
    }
}
?>
