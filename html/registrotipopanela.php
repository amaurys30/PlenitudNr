<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_panela = $_POST['tipo_panela'];
    $precio_unitario = $_POST['precio_unitario'];

    // Verificar si ya existe un precio para este tipo de panela
    $query = "SELECT * FROM PrecioPanela WHERE tipo_panela = '$tipo_panela'";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) > 0) {
        // Si ya existe, mostrar un mensaje de error
        echo "<div class='alert alert-danger' role='alert'>
                El tipo de panela ya existe. No se puede agregar nuevamente.
              </div>";
    } else {
        // Si no existe, agregar el nuevo tipo de panela
        $sql = "INSERT INTO PrecioPanela (tipo_panela, precio_unitario) VALUES ('$tipo_panela', '$precio_unitario')";
        if (mysqli_query($conexion, $sql)) {
            echo "<div class='alert alert-success' role='alert'>
                    Precio de panela agregado correctamente.
                  </div>";
        } else {
            echo "Error al agregar el precio de panela: " . mysqli_error($conexion);
        }
    }
}
mysqli_close($conexion);
?>
