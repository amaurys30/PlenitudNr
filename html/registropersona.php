<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $fecharegistro = $_POST['fecharegistro'];

    // Validar si la cédula ya está registrada
    $sql_check = "SELECT * FROM Persona WHERE cedula = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $cedula);
    mysqli_stmt_execute($stmt_check);
    $resultado_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($resultado_check) > 0) {
        // La cédula ya está registrada
        echo "<div class='alert alert-warning'>La persona ya fue registrada.</div>";
    } else {
        // La cédula no está registrada, proceder a insertar
        $sql_insert = "INSERT INTO Persona (nombre, cedula, telefono, direccion,fecha_registro) VALUES (?, ?, ?, ?,?)";
        $stmt_insert = mysqli_prepare($conexion, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, "sssss", $nombre, $cedula, $telefono, $direccion, $fecharegistro);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            echo "<div class='alert alert-success'>Persona registrada exitosamente.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al registrar la persona: " . mysqli_error($conexion) . "</div>";
        }
        
        mysqli_stmt_close($stmt_insert);
    }
    
    mysqli_stmt_close($stmt_check);
    mysqli_close($conexion);
}
?>
