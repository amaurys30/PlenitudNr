<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_precio = $_POST['id_precio'];
    $tipo_panela = $_POST['tipo_panela'];
    $precio_unitario = $_POST['precio_unitario'];

    // Actualizar el tipo de panela
    $sql = "UPDATE PrecioPanela 
            SET tipo_panela = '$tipo_panela', precio_unitario = '$precio_unitario' 
            WHERE id_precio = $id_precio";

    if (mysqli_query($conexion, $sql)) {
        header("Location: index.php?registro=actualizado");
    } else {
        header("Location: index.php?registro=error");
    }
}

mysqli_close($conexion);
?>
