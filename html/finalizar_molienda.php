<?php
include 'conexion.php'; // Conexión a la base de datos

// Obtener el ID de la molienda
$id_molienda = $_GET['id'];

// Consulta para obtener el estado actual de la molienda
$sql_estado = "SELECT estado, fecha_fin FROM molienda WHERE id_molienda = $id_molienda";
$resultado_estado = mysqli_query($conexion, $sql_estado);
$molienda = mysqli_fetch_assoc($resultado_estado);

// Verificar si la molienda ya está inactiva
if ($molienda['estado'] == 'inactiva') {
    echo "<script>alert('Esta molienda ya está finalizada.'); window.location.href = 'index.php';</script>";
    exit;
}

// Consulta para verificar si hay pagos pendientes
$sql_pendientes = "SELECT 
                    pa.id_participacion 
                   FROM participacion pa
                   LEFT JOIN pago p ON pa.id_participacion = p.id_participacion
                   WHERE p.id_participacion IS NULL AND pa.id_molienda = $id_molienda";

$resultado_pendientes = mysqli_query($conexion, $sql_pendientes);

// Verificar si hay pagos pendientes
if (mysqli_num_rows($resultado_pendientes) > 0) {
    echo "<script>alert('Hay pagos pendientes. No se puede finalizar la molienda.'); window.location.href = 'index.php';</script>";
    exit;
}

// Si no hay pagos pendientes, finalizar la molienda
$fecha_fin = date('Y-m-d H:i:s'); // Fecha actual

$sql_finalizar = "UPDATE molienda 
                  SET estado = 'inactiva', fecha_fin = '$fecha_fin' 
                  WHERE id_molienda = $id_molienda";

if (mysqli_query($conexion, $sql_finalizar)) {
    echo "<script>alert('La molienda ha sido finalizada correctamente.'); window.location.href = 'index.php';</script>";
} else {
    echo "<script>alert('Error al finalizar la molienda.'); window.location.href = 'index.php';</script>";
}

mysqli_close($conexion);
?>
