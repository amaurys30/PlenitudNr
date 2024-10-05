<?php
include 'conexion.php';

$response = ['status' => 'error', 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    // Validar si la cédula ya está registrada
    $sql_check = "SELECT * FROM Persona WHERE cedula = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $cedula);
    mysqli_stmt_execute($stmt_check);
    $resultado_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($resultado_check) > 0) {
        $response['message'] = "La persona ya fue registrada.";
    } else {
        // La cédula no está registrada, proceder a insertar
        $sql_insert = "INSERT INTO Persona (nombre, cedula, telefono, direccion, fecha_registro) VALUES (?, ?, ?, ?, NOW())";
        $stmt_insert = mysqli_prepare($conexion, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, "ssss", $nombre, $cedula, $telefono, $direccion);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            $response['status'] = 'success';
            $response['message'] = "Persona registrada exitosamente.";
        } else {
            $response['message'] = "Error al registrar la persona: " . mysqli_error($conexion);
        }
        
        mysqli_stmt_close($stmt_insert);
    }
    
    mysqli_stmt_close($stmt_check);
}

mysqli_close($conexion);

// Devolver respuesta en JSON
echo json_encode($response);
?>
