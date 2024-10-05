<?php
include 'conexion.php';

$id_persona = $_GET['id'];

$sql = "SELECT * FROM Persona WHERE id_persona = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_persona);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($resultado)) {
    echo json_encode($row);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Persona no encontrada']);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
