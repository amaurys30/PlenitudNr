<?php
require_once 'conexion.php';

if (isset($_GET['id'])) {
    $id_precio = $_GET['id'];

    // Eliminar el tipo de panela
    $sql = "DELETE FROM PrecioPanela WHERE id_precio = $id_precio";

    if (mysqli_query($conexion, $sql)) {
        header("Location: principal.php?registro=eliminado");
    } else {
        header("Location: principal.php?registro=error");
    }
}

mysqli_close($conexion);
?>
