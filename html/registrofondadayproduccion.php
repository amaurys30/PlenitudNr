<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_molienda = $_POST['id_molienda'];
    $cantidad_litros = $_POST['cantidad_litros'];
    $cantidad_grande = $_POST['cantidad_grande'];
    $cantidad_mediana = $_POST['cantidad_mediana'];
    $cantidad_pequena = $_POST['cantidad_pequena'];

    // Iniciar transacción
    mysqli_begin_transaction($conexion);

    try {
        // Insertar fondada
        $sql_fondada = "INSERT INTO Fondada (id_molienda, cantidad_litros, fecha_agregada) VALUES ('$id_molienda', '$cantidad_litros', NOW())";
        mysqli_query($conexion, $sql_fondada);
        $id_fondada = mysqli_insert_id($conexion);

        // Obtener precios actuales
        $precio_grande = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT precio_unitario FROM PrecioPanela WHERE tipo_panela = 'grande'"))['precio_unitario'];
        $precio_mediana = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT precio_unitario FROM PrecioPanela WHERE tipo_panela = 'mediana'"))['precio_unitario'];
        $precio_pequena = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT precio_unitario FROM PrecioPanela WHERE tipo_panela = 'pequeña'"))['precio_unitario'];

        // Insertar producción de panela
        $sql_produccion_grande = "INSERT INTO ProduccionPanela (id_fondada, tipo_panela, cantidad_panela, precio_unitario) VALUES ('$id_fondada', 'grande', '$cantidad_grande', '$precio_grande')";
        $sql_produccion_mediana = "INSERT INTO ProduccionPanela (id_fondada, tipo_panela, cantidad_panela, precio_unitario) VALUES ('$id_fondada', 'mediana', '$cantidad_mediana', '$precio_mediana')";
        $sql_produccion_pequena = "INSERT INTO ProduccionPanela (id_fondada, tipo_panela, cantidad_panela, precio_unitario) VALUES ('$id_fondada', 'pequeña', '$cantidad_pequena', '$precio_pequena')";

        mysqli_query($conexion, $sql_produccion_grande);
        mysqli_query($conexion, $sql_produccion_mediana);
        mysqli_query($conexion, $sql_produccion_pequena);

        // Confirmar transacción
        mysqli_commit($conexion);
        header("Location: molienda.php?id_molienda=<?php echo $id_molienda; ?>&registro=exitoso");
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        mysqli_rollback($conexion);
        //echo "Error al agregar fondada: " . $e->getMessage();
        header("Location: molienda.php?id_molienda=<?php echo $id_molienda; ?>&registro=errorFondada");
    }
}

mysqli_close($conexion);
?>
