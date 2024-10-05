<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_persona = $_POST['id_persona'];
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $sql_update = "UPDATE Persona SET nombre = ?, cedula = ?, telefono = ?, direccion = ? WHERE id_persona = ?";
    $stmt_update = mysqli_prepare($conexion, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "ssssi", $nombre, $cedula, $telefono, $direccion, $id_persona);
    
    if (mysqli_stmt_execute($stmt_update)) {
        echo json_encode(['status' => 'success', 'message' => 'Persona actualizada exitosamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar: ' . mysqli_error($conexion)]);
    }
    
    mysqli_stmt_close($stmt_update);
    mysqli_close($conexion);
}
?>
