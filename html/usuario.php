<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            margin-top: 80px;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            font-weight: 700;
            color: #343a40;
        }
        label {
            font-weight: bold;
        }
        .btn-primary {
            width: 100%;
        }
        .btn-success {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card p-4">
            <h1>Registro de Usuario</h1>
            <?php
            if (isset($_GET['registro'])) {
                if ($_GET['registro'] == 'exitoso') {
                    echo '<div id="mensaje-exito" class="alert alert-success mt-3">¡Registro exitoso!</div>';
                } elseif ($_GET['registro'] == 'correoenuso') {
                    echo '<div id="mensaje-exito" class="alert alert-danger mt-3">El correo ya existe, elige otro correo.</div>';
                } elseif ($_GET['registro'] == 'error') {
                    echo '<div id="mensaje-exito" class="alert alert-danger mt-3">Error al registrar el usuario.</div>';
                }
            }
            ?>
            <form action="registrousuario.php" method="POST">
                <div class="form-group mb-3">
                    <label for="nombre_usuario">Nombre de Usuario</label>
                    <input type="text" name="nombre_usuario" id="nombre_usuario" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="correo">Correo</label>
                    <input type="email" name="correo" id="correo" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" name="contrasena" id="contrasena" class="form-control" required>
                </div>
                <div class="form-group mb-4">
                    <label for="tipo_usuario">Tipo de Usuario</label>
                    <select name="tipo_usuario" id="tipo_usuario" class="form-control">
                        <option value="general">General</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </form>
            <a href="principal.php" class="btn btn-success mt-3">Volver</a>
        </div>
    </div>
    <script src="../js/utilidades.js"></script>
</body>
</html>
