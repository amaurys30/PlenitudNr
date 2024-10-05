<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_labor = $_POST['nombrelabor'];
    $precio_por_fondada = $_POST['precio'];

    // Verificar si la labor ya existe
    $sql_check = "SELECT * FROM Labor WHERE nombre_labor = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $nombre_labor);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);

    // Si existe, redirigir con un mensaje de error
    if (mysqli_num_rows($result_check) > 0) {
        header("Location: index.php?registro=Laboryaexiste");
    } else {
        // Insertar la nueva labor en la base de datos
        $sql_insert = "INSERT INTO Labor (nombre_labor, precio_por_fondada) VALUES (?, ?)";
        $stmt_insert = mysqli_prepare($conexion, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, "sd", $nombre_labor, $precio_por_fondada);

        if (mysqli_stmt_execute($stmt_insert)) {
            header("Location: index.php?registro=Laborexitoso");
        } else {
            header("Location: index.php?registro=Laborerror");
        }

        mysqli_stmt_close($stmt_insert);
    }

    mysqli_stmt_close($stmt_check);
    mysqli_close($conexion);
}
?>
