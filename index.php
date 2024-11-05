<?php
    if (isset($_GET['registro'])) {
        // Redirigir después de 10 segundos
        header("Refresh:10; url=index.php");        
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Imagen de fondo */
        .cuerpoinicio {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('recursos/imagenes/fondo.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .divcontainer {
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex-grow: 1;
        }

        .titulo {
            font-size: 30px;
            text-align: center;
            margin-top: 30px;
            font-weight: 300;
            color: #fff;
        }

        /* Estilos del formulario */
        .login-form {
            width: 350px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            margin: auto;
        }

        /* Estilos para el botón de entrar */
        .btn-login {
            width: 100%;
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }

        /* Link de 'olvidaste tu contraseña' */
        .forgot-link {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }

        /* Footer minimalista */
        .piedepaginainicio {
            text-align: center;
            color: #fff;
            font-size: 14px;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.7);
        }
        .custom-image {
            border-radius: 50%;
            border: 4px solid #fff; /* Puedes ajustar el color y grosor del borde */
            width: 90px; /* Ajusta el tamaño de la imagen */
            height: 90px;
            object-fit: cover; /* Para asegurarse de que la imagen no se deforme */
            margin-block: 10px;
        }

        /* Forzar que el footer siempre esté al pie de la página */
        .cuerpoinicio {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .divcontainer {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
</head>
<body class="cuerpoinicio">
<?php
    if (isset($_GET['contrasena'])) {
        if ($_GET['contrasena'] == 'incorrecta') {
            header("Location: index.php?registro=contrasenaincorrecta");
        } elseif ($_GET['contrasena'] == 'usuarionoencontrado') {
            header("Location: index.php?registro=userincorrecta");
        }
    }
?>

<?php
    if (isset($_GET['registro'])) {
        if ($_GET['registro'] == 'contrasenaincorrecta') {
            echo '<div id="mensaje-exito" class="alert alert-danger mt-3">Contraseña incorrecta</div>';
        } elseif ($_GET['registro'] == 'userincorrecta') {
            echo '<div id="mensaje-exito" class="alert alert-danger mt-3">El usuario no fue encontrado</div>';
        }
    }
?>

    <div class="divcontainer">
        <h1 class="titulo">Inicia sesión en tu cuenta Plenitud Nr</h1>
        <div class="login-form">
            <img src="recursos/imagenes/fondo.jpg" alt="Logo" class="custom-image shadow">
            <form action="html/login.php" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required>
                    <label for="usuario">Correo</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="Contraseña" required>
                    <label for="contraseña">Contraseña</label>
                </div>
                <button type="submit" class="btn-login">Entrar</button>
                <div class="forgot-link">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalRecuperar">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalRecuperar" tabindex="-1" aria-labelledby="modalRecuperarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRecuperarLabel">Recuperar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Por favor comunicarse con el administrador.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Footer minimalista -->
    <footer class="piedepaginainicio">
        <p>&copy; 2024 Plenitud Nr. Todos los derechos reservados.</p>
    </footer>

    <script src="js/utilidades.js"></script>
    <!-- Enlace a Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JavaScript (incluye Popper.js) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

</body>
</html>
