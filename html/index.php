<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Producción Panela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Plenitud Naranjal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="mostrarSeccion('molienda-content')">Crear Molienda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="mostrarSeccion('ver-moliendas-content')">Ver Moliendas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="mostrarSeccion('personas-content')">Registrar Personas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="mostrarSeccion('labores-content')">Registrar Labores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="mostrarSeccion('reporte-content')">Reporte Pagos y Producción</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="mostrarSeccion('tipo-panela-content')">Tipos de Panela</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Crear Molienda -->
        <div id="molienda-content" class="contenido-seccion">
            <h3>Crear Nueva Molienda</h3>
            <form action="crearmolienda.php" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción de la molienda" required>
                    <label for="descripcion">Descripción</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="datetime-local" class="form-control" id="fechainicio" name="fechainicio" placeholder="Fecha de inicio" required>
                    <label for="fecha_inicio">Fecha de Inicio</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="datetime-local" class="form-control" id="fechafin" name="fechafin" placeholder="Fecha de finalización">
                    <label for="fecha_fin">Fecha de Finalización</label>
                </div>
                <input type="submit" value="Crear Molienda" class="btn btn-primary">
            </form>
        </div>

        <!-- Ver Moliendas -->
        <div class="contenido-seccion" id="ver-moliendas-content" style="display:none;">
            <h5 class="card-title">Listado de Moliendas</h5>
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Descripción</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once('conexion.php');
                    $sql = "SELECT * FROM Molienda";
                    $resultado = mysqli_query($conexion, $sql);
                    if (mysqli_num_rows($resultado) > 0) {
                        $i = 0;
                        while ($fila = mysqli_fetch_array($resultado)) {
                            $i++;
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo htmlspecialchars($fila["descripcion"] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($fila["fecha_inicio"] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($fila["fecha_fin"] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($fila["estado"] ?? ''); ?></td>
                                <td>
                                    <a href="molienda.php?id_molienda=<?php echo $fila['id_molienda']; ?>" class="btn btn-info">Ir a Molienda</a>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarModal-<?php echo $fila['id_molienda']; ?>">Editar</button>
                                    <a href="finalizar_molienda.php?id=<?php echo $fila['id_molienda']; ?>" class="btn btn-danger">Finalizar</a>
                                </td>
                            </tr>

                            <!-- Modal para editar molienda -->
                            <div class="modal fade" id="editarModal-<?php echo $fila['id_molienda']; ?>" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editarModalLabel">Editar Molienda</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="crearmolienda.php" method="POST">
                                                <input type="hidden" name="id_molienda" value="<?php echo $fila['id_molienda']; ?>">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($fila['descripcion'] ?? ''); ?>" required>
                                                    <label for="descripcion">Descripción</label>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <input type="datetime-local" class="form-control" id="fechainicio" name="fechainicio" value="<?php echo date('Y-m-d\TH:i', strtotime($fila['fecha_inicio'])); ?>" required>
                                                    <label for="fechainicio">Fecha de Inicio</label>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <input type="datetime-local" class="form-control" id="fechafin" name="fechafin" value="<?php echo isset($fila['fecha_fin']) ? date('Y-m-d\TH:i', strtotime($fila['fecha_fin'])) : ''; ?>">
                                                    <label for="fechafin">Fecha de Finalización</label>
                                                </div>
                                                <input type="submit" value="Actualizar Molienda" class="btn btn-primary">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6'>No se encontraron registros</td></tr>";
                    }
                    mysqli_close($conexion);
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Registrar Personas -->
        <div id="personas-content" class="contenido-seccion" style="display:none;">
            <h3>Registrar Persona</h3>
            <form action="registropersona.php" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre de la persona" required>
                    <label for="nombre">Nombre</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Cédula" required>
                    <label for="cedula">Cédula</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
                    <label for="telefono">Teléfono</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección">
                    <label for="direccion">Dirección</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="datetime-local" class="form-control" id="fecharegistro" name="fecharegistro" placeholder="Fecha de registro">
                    <label for="fecharegistro">Fecha de registro</label>
                </div>
                <button type="submit" class="btn btn-primary">Registrar Persona</button>
            </form>
        </div>

        <!-- Registrar Labores -->
        <div id="labores-content" class="contenido-seccion" style="display:none;">
            <h3>Registrar Labor</h3>
            <form action="registrolabor.php" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nombrelabor" name="nombrelabor" placeholder="Nombre de la labor" required>
                    <label for="nombrelabor">Nombre de la labor</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="precio" name="precio" placeholder="Precio por fondada" required>
                    <label for="precio">Precio por fondada</label>
                </div>
                <button type="submit" class="btn btn-primary">Registrar Labor</button>
            </form>
        </div>

        <!-- Reporte Pagos y Producción -->
        <div id="reporte-content" class="contenido-seccion" style="display:none;">
            <h3>Reporte Pagos y Producción</h3>
            <!-- Aquí iría el código del reporte generado en PHP, con tablas y cálculos -->
            <p>Aquí se mostrará el reporte detallado de pagos y producción.</p>
        </div>

        <!-- Tipos de Panela -->
        <div id="tipo-panela-content" class="contenido-seccion" style="display:none;">
            <h3>Registrar Tipo de Panela</h3>
            <form action="registrotipopanela.php" method="POST">
            <div class="form-floating mb-3">
                <select class="form-control" id="tipo_panela" name="tipo_panela" required>
                    <option value="grande">Grande</option>
                    <option value="mediana">Mediana</option>
                    <option value="pequeña">Pequeña</option>
                </select>
                <label for="tipo_panela">Tipo de Panela</label>
            </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="precio_unitario" name="precio_unitario" placeholder="Precio por unidad" required>
                    <label for="precio_unitario">Precio por unidad</label>
                </div>
                <button type="submit" class="btn btn-primary">Registrar Tipo de Panela</button>
            </form>
        </div>
    </div>

    <script>
        // Función para mostrar la sección seleccionada
        function mostrarSeccion(seccionId) {
            // Oculta todas las secciones
            const secciones = document.querySelectorAll('.contenido-seccion');
            secciones.forEach(seccion => {
                seccion.style.display = 'none';
            });

            // Muestra solo la sección seleccionada
            const seccionMostrada = document.getElementById(seccionId);
            if (seccionMostrada) {
                seccionMostrada.style.display = 'block';
            } else {
                console.error('Sección no encontrada: ', seccionId);
            }
        }

        // Mostrar la primera sección por defecto (ejemplo: "Crear Molienda")
        document.addEventListener('DOMContentLoaded', function() {
            mostrarSeccion('molienda-content'); // Cambia esto a la sección que quieras mostrar por defecto
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
