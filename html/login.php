<?php
session_start();
include 'conexion.php'; // Asegúrate de incluir tu archivo de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['usuario'];
    $contrasena = $_POST['contraseña'];

    // Consulta para obtener la información del usuario por correo
    $sql = "SELECT id_usuario, nombre_usuario, contrasena, tipo_usuario FROM usuarios WHERE correo = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        // Verificar la contraseña
        if (password_verify($contrasena, $fila['contrasena'])) {
            // Iniciar sesión: guardar información en las variables de sesión
            $_SESSION['id_usuario'] = $fila['id_usuario'];
            $_SESSION['nombre_usuario'] = $fila['nombre_usuario'];
            $_SESSION['tipo_usuario'] = $fila['tipo_usuario'];

            // Redirigir según el tipo de usuario
            if ($fila['tipo_usuario'] == 'administrador') {
                header("Location: principal.php");
            } else {
                header("Location: principal.php");
            }
            exit();
        } else {
            echo "Contraseña incorrecta.";
            header("Location: ../index.php?contrasena=incorrecta");
        }
    } else {
        header("Location: ../index.php?contrasena=usuarionoencontrado");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}
?>
