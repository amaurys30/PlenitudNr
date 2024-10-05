<?php
// Conectar a la base de datos
require_once 'conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $id_labor = $_POST['id_labor'];
    $nombre_labor = $_POST['nombrelabor'];
    $precio_por_fondada = $_POST['precio'];

    // Validar datos
    if (!empty($nombre_labor) && is_numeric($precio_por_fondada) && $precio_por_fondada >= 0) {
        // Actualizar la labor en la base de datos
        $query = "UPDATE labor SET nombre_labor = ?, precio_por_fondada = ? WHERE id_labor = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sdi", $nombre_labor, $precio_por_fondada, $id_labor);

        if ($stmt->execute()) {
            // Redirigir con éxito
            header("Location: index.php?registro=actualizacionlaborexitoso");
        } else {
            // Error en la actualización
            header("Location: index.php?registro=actualizacionlaborerror");
        }

        $stmt->close();
    } else {
        // Datos inválidos
        header("Location: index.php?registro=actualizacionlaborerror");
    }
}

// Cerrar la conexión
$conexion->close();
?>
