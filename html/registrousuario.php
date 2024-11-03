<?php
include 'conexion.php'; // Asegúrate de incluir tu archivo de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Verificar si el correo ya está registrado
    $sql_check = "SELECT id_usuario FROM usuarios WHERE correo = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $correo);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        // El correo ya está registrado
        echo "El correo ya está en uso.";
        header("Location: usuario.php?registro=correoenuso");
    } else {
        // Encriptar la contraseña
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario
        $sql_insert = "INSERT INTO usuarios (nombre_usuario, correo, contrasena, tipo_usuario) VALUES (?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conexion, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, "ssss", $nombre_usuario, $correo, $contrasena_hash, $tipo_usuario);

        if (mysqli_stmt_execute($stmt_insert)) {
            header("Location: usuario.php?registro=exitoso");
        } else {
            header("Location: principal.php?registro=error");
        }

        mysqli_stmt_close($stmt_insert);
    }

    mysqli_stmt_close($stmt_check);
    mysqli_close($conexion);
}
?>
