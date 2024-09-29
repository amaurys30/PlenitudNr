<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_labor = $_POST['nombre_labor'];
    $precio_por_fondada = $_POST['precio_por_fondada'];

    // Insertar la nueva labor en la base de datos
    $sql_insert = "INSERT INTO Labor (nombre_labor, precio_por_fondada) VALUES (?, ?)";
    $stmt_insert = mysqli_prepare($conexion, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "sd", $nombre_labor, $precio_por_fondada);

    if (mysqli_stmt_execute($stmt_insert)) {
        echo "<div class='alert alert-success'>Labor registrada exitosamente.</div>";
        echo "<a href='index.php' class='btn btn-success'>Volver</a>";
    } else {
        echo "<div class='alert alert-danger'>Error al registrar la labor: " . mysqli_error($conexion) . "</div>";
    }
    
    mysqli_stmt_close($stmt_insert);
    mysqli_close($conexion);
}
?>
