<?php
include 'conexion.php'; // Incluye la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $id_molienda = $_POST['id_molienda'];
    $id_persona = $_POST['id_persona'];
    $id_labor = $_POST['id_labor'];
    $cantidad_fondadas = $_POST['cantidad_fondadas'];
    $fecha_participacion = $_POST['fechaparticipacion'];
    $es_procesamiento = isset($_POST['procesamiento']) ? 1 : 0; // Checkbox es procesamiento

    // Verificar que la cantidad de fondadas no exceda el límite
    $sql_total_fondadas = "SELECT COUNT(*) AS total_fondadas FROM fondada WHERE id_molienda = $id_molienda";
    $resultado_fondadas = mysqli_query($conexion, $sql_total_fondadas);
    $total_fondadas = mysqli_fetch_assoc($resultado_fondadas)['total_fondadas'];

    // Verificar cuántas personas ya están asignadas al procesamiento
    if ($es_procesamiento) {
        $sql_procesamiento = "SELECT COUNT(*) AS total_procesamiento FROM participacion WHERE id_molienda = $id_molienda AND es_procesamiento = 1";
        $resultado_procesamiento = mysqli_query($conexion, $sql_procesamiento);
        $total_procesamiento = mysqli_fetch_assoc($resultado_procesamiento)['total_procesamiento'];

        // Si ya hay 6 personas en el procesamiento, no permitir agregar más
        if ($total_procesamiento >= 6) {
            echo "Error: Ya hay 6 personas asignadas al procesamiento para esta molienda.";
            exit;
        }

        // Obtener el precio de la labor de procesamiento
        $sql_precio_procesamiento = "SELECT precio_por_fondada FROM labor WHERE id_labor = $id_labor";
        $resultado_precio = mysqli_query($conexion, $sql_precio_procesamiento);
        $precio_procesamiento = mysqli_fetch_assoc($resultado_precio)['precio_por_fondada'];

        // Calcular el monto total para el procesamiento (precio * total fondadas / 6)
        $monto_total = ($precio_procesamiento * $total_fondadas) / 6;

    } else {
        // Obtener el precio de la labor seleccionada
        $sql_precio_labor = "SELECT precio_por_fondada FROM labor WHERE id_labor = $id_labor";
        $resultado_precio = mysqli_query($conexion, $sql_precio_labor);
        $precio_labor = mysqli_fetch_assoc($resultado_precio)['precio_por_fondada'];

        // Calcular el monto total para la labor no asociada a procesamiento
        $monto_total = $precio_labor * $cantidad_fondadas;
    }

    // Insertar la participación en la base de datos
    $sql_insert = "INSERT INTO participacion (id_persona, id_molienda, id_labor, cantidad_fondadas, fecha_participacion, es_procesamiento, monto_total)
                   VALUES ('$id_persona', '$id_molienda', '$id_labor', '$cantidad_fondadas', '$fecha_participacion', '$es_procesamiento', '$monto_total')";

    if (mysqli_query($conexion, $sql_insert)) {
        // Redireccionar de vuelta a la página de participaciones con un mensaje de éxito
        header("Location: participacion.php?id_molienda=$id_molienda&mensaje=Participación agregada correctamente");
    } else {
        // Mostrar error si la inserción falla
        echo "Error: " . mysqli_error($conexion);
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
