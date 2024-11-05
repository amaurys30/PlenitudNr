<?php
include 'conexion.php'; // Incluir la conexión a la base de datos

// Verificar si se ha enviado el formulario con los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_molienda = $_POST['id_molienda'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $cantidad = $_POST['cantidad'] ?? null;
    $monto = $_POST['monto'] ?? null;

    // Validar que todos los campos requeridos tengan un valor
    if ($id_molienda && $nombre && $descripcion && $cantidad && $monto) {
        // Preparar la consulta para insertar el nuevo gasto
        $sql = "INSERT INTO gastos (id_molienda, nombre, descripcion, cantidad, monto, fecha_gasto) 
                VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";

        // Preparar la sentencia
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Enlazar los parámetros
            mysqli_stmt_bind_param($stmt, "isssd", $id_molienda, $nombre, $descripcion, $cantidad, $monto);

            // Ejecutar la consulta
            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Gasto registrado exitosamente.";
            } else {
                $mensaje = "Error al registrar el gasto: " . mysqli_error($conexion);
            }

            // Cerrar la sentencia
            mysqli_stmt_close($stmt);
        } else {
            $mensaje = "Error en la preparación de la consulta: " . mysqli_error($conexion);
        }
    } else {
        $mensaje = "Todos los campos son obligatorios.";
    }
    
    // Redirigir a la página de gastos con un mensaje de éxito o error
    header("Location: gastos.php?id_molienda=$id_molienda&mensaje=" . urlencode($mensaje));
    exit;
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
