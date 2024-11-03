<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_panela = $_POST['tipo_panela'];
    $precio_unitario = $_POST['precio_unitario'];

    // Verificar si ya existe un precio para este tipo de panela
    $query = "SELECT * FROM PrecioPanela WHERE tipo_panela = '$tipo_panela'";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) > 0) {
        // Si ya existe, redirigir con un mensaje de error
        header("Location: principal.php?registro=existe");
    } else {
        // Si no existe, agregar el nuevo tipo de panela
        $sql = "INSERT INTO PrecioPanela (tipo_panela, precio_unitario) VALUES ('$tipo_panela', '$precio_unitario')";
        if (mysqli_query($conexion, $sql)) {
            header("Location: principal.php?registro=exitoso");
        } else {
            header("Location: principal.php?registro=error");
        }
    }
}

mysqli_close($conexion);
?>
