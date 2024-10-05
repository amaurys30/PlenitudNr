<?php
// Verificar si hay un mensaje de éxito o error
$mensaje = $_GET['actualizacion'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Producción Panela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Función para ocultar todas las secciones y mostrar la seleccionada
        function mostrarSeccion(seccionId) {
            // Ocultar todas las secciones
            var secciones = document.querySelectorAll('.contenido-seccion');
            secciones.forEach(function (seccion) {
                seccion.style.display = 'none';
            });

            // Mostrar la sección seleccionada
            document.getElementById(seccionId).style.display = 'block';
        }

        // Mostrar por defecto la primera sección
        window.onload = function() {
            mostrarSeccion('ver-moliendas-content');
        };
    </script>
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
                        <a class="nav-link" href="#" onclick="mostrarSeccion('molienda-content')">Crear Molienda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="mostrarSeccion('ver-moliendas-content')">Ver Moliendas</a>
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
            <!-- Mostrar mensajes de éxito o error -->
            <?php if ($mensaje): ?>
                <div id="mensaje-exito" class="alert alert-success">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>


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
                                    <a href="finalizar_molienda.php?id=<?php echo $fila['id_molienda']; ?>" class="btn btn-danger" 
                                    onclick="return confirm('¿Estás seguro de que deseas finalizar esta molienda?')">Finalizar</a>
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
                                            <form action="editarmolienda.php" method="POST">
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
                                                <div class="form-floating mb-3">
                                                    <select class="form-control" id="estado" name="estado" required>
                                                        <option value="activa" <?php echo ($fila['estado'] == 'activa') ? 'selected' : ''; ?>>Activa</option>
                                                        <option value="inactiva" <?php echo ($fila['estado'] == 'inactiva') ? 'selected' : ''; ?>>Inactiva</option>
                                                    </select>
                                                    <label for="estado">Estado</label>
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
                    //mysqli_close($conexion);
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Registrar Personas -->
        <div id="personas-content" class="contenido-seccion" style="display:none;">
            <!-- Botón para abrir modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registroPersonaModal" id="mostrarRegistroBtn">
            Registrar Persona
            </button>
            <!-- Modal para registrar persona -->
            <div class="modal fade" id="registroPersonaModal" tabindex="-1" aria-labelledby="registroPersonaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registroPersonaLabel">Registrar Persona</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formRegistroPersona">
                    <input type="hidden" id="id_persona" name="id_persona">
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
                    <button type="button" class="btn btn-primary" id="registrarPersonaBtn">Registrar Persona</button>
                    </form>
                </div>
                </div>
            </div>
            </div>
            <br>
            <br>
            <!-- Tabla para listar personas -->
            <input type="text" id="busqueda" class="form-control mb-3" placeholder="Buscar por nombre...">
            <div id="listado-personas">
            <!-- Aquí se cargarán los resultados de la paginación -->
            </div>
        </div>

        <!-- Registrar Labores -->
        <div id="labores-content" class="contenido-seccion" style="display:none;">
            <div class="row">
                <!-- Columna del formulario para registrar labor -->
                <div class="col-md-6">
                    <h3>Registrar Labor</h3>
                    <form action="registrolabor.php" method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nombrelabor" name="nombrelabor" placeholder="Nombre de la labor" required>
                            <label for="nombrelabor">Nombre de la labor</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="precio" name="precio" placeholder="Precio por fondada" step="0.01" required>
                            <label for="precio">Precio por fondada</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrar Labor</button>
                        
                        <!-- Mensaje de éxito o error -->
                        <?php
                        if (isset($_GET['registro'])) {
                            if ($_GET['registro'] == 'Laborexitoso') {
                                echo '<div id="mensaje-exito" class="alert alert-success mt-3">¡Registro exitoso!</div>';
                            } elseif ($_GET['registro'] == 'Laboryaexiste') {
                                echo '<div id="mensaje-exito" class="alert alert-danger mt-3">La labor ya existe, elige otro nombre.</div>';
                            }elseif ($_GET['registro'] == 'Laborerror') {
                                echo '<div id="mensaje-exito" class="alert alert-danger mt-3">Error al registrar la labor.</div>';
                            }elseif ($_GET['registro'] == 'actualizacionlaborexitoso') {
                                echo '<div id="mensaje-exito" class="alert alert-success mt-3">La actualizacion de la labor fue exitosa.</div>';
                            }elseif ($_GET['registro'] == 'actualizacionlaborerror') {
                                echo '<div id="mensaje-exito" class="alert alert-danger mt-3">Error al actualizar la labor.</div>';
                            }
                        }
                        ?>
                    </form>
                </div>

                <!-- Columna para mostrar la lista de labores -->
                <div class="col-md-6">
                    <h3>Listado de Labores</h3>
                    <table class="table mt-4">
                        <thead>
                            <tr>
                                <th>Nombre de la Labor</th>
                                <th>Precio por Fondada</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once 'conexion.php';
                            $query = "SELECT * FROM labor";
                            $result = mysqli_query($conexion, $query);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nombre_labor']); ?></td>
                                        <td><?php echo number_format($row['precio_por_fondada'], 2); ?></td>
                                        <td>
                                            <!-- Botón para editar con modal -->
                                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarModal2-<?php echo $row['id_labor']; ?>">Editar</button>
                                        </td>
                                    </tr>

                                    <!-- Modal para editar labor -->
                                    <div class="modal fade" id="editarModal2-<?php echo $row['id_labor']; ?>" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editarModalLabel">Editar Labor</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="editarlabor.php" method="POST">
                                                        <input type="hidden" name="id_labor" value="<?php echo $row['id_labor']; ?>">
                                                        <div class="form-floating mb-3">
                                                            <input type="text" class="form-control" name="nombrelabor" value="<?php echo htmlspecialchars($row['nombre_labor']); ?>" required>
                                                            <label for="nombrelabor">Nombre de la labor</label>
                                                        </div>
                                                        <div class="form-floating mb-3">
                                                            <input type="number" class="form-control" name="precio" value="<?php echo $row['precio_por_fondada']; ?>" step="0.01" required>
                                                            <label for="precio">Precio por fondada</label>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Actualizar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='3'>No hay labores registradas.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Reporte Pagos y Producción -->
        <div id="reporte-content" class="contenido-seccion" style="display:none;">
            <h3>Reporte Pagos y Producción</h3>
            <!-- Aquí iría el código del reporte generado en PHP, con tablas y cálculos -->
            <p>Aquí se mostrará el reporte detallado de pagos y producción.</p>
        </div>

        <!-- Tipos de Panela -->
        <div id="tipo-panela-content" class="contenido-seccion" style="display:none;">
            <div class="row">
                <!-- Columna del formulario para registrar tipo de panela -->
                <div class="col-md-6">
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

                    <!-- Mensaje de éxito o error -->
                    <?php
                    if (isset($_GET['registro'])) {
                        if ($_GET['registro'] == 'exitoso') {
                            echo '<div id="mensaje-exito" class="alert alert-success mt-3">¡Registro exitoso!</div>';
                        } elseif ($_GET['registro'] == 'existe') {
                            echo '<div id="mensaje-exito" class="alert alert-danger mt-3">El tipo de panela ya existe.</div>';
                        } elseif ($_GET['registro'] == 'error') {
                            echo '<div id="mensaje-exito" class="alert alert-danger mt-3">Error al registrar el tipo de panela.</div>';
                        }
                    }
                    ?>
                </div>

                <!-- Columna para mostrar la lista de tipos de panela -->
                <div class="col-md-6">
                    <h3>Listado de Tipos de Panela</h3>
                    <table class="table mt-4">
                        <thead>
                            <tr>
                                <th>Tipo de Panela</th>
                                <th>Precio Unitario</th>
                                <th>Fecha de Actualización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once 'conexion.php';
                            $query = "SELECT * FROM preciopanela";
                            $result = mysqli_query($conexion, $query);
                            
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['tipo_panela']); ?></td>
                                        <td><?php echo number_format($row['precio_unitario'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($row['fecha_actualizacion']); ?></td>
                                        <td>
                                            <!-- Botón para editar con modal -->
                                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarModal1-<?php echo $row['id_precio']; ?>">Editar</button>
                                            
                                            <!-- Botón para eliminar -->
                                            <a href="eliminartipopanela.php?id=<?php echo $row['id_precio']; ?>" class="btn btn-danger" 
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este tipo de panela?');">
                                            Eliminar
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Modal para editar tipo de panela -->
                                    <div class="modal fade" id="editarModal1-<?php echo $row['id_precio']; ?>" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editarModalLabel">Editar Tipo de Panela</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="editartipopanela.php" method="POST">
                                                        <input type="hidden" name="id_precio" value="<?php echo $row['id_precio']; ?>">
                                                        <div class="form-floating mb-3">
                                                            <select class="form-control" name="tipo_panela" required>
                                                                <option value="grande" <?php echo ($row['tipo_panela'] == 'grande') ? 'selected' : ''; ?>>Grande</option>
                                                                <option value="mediana" <?php echo ($row['tipo_panela'] == 'mediana') ? 'selected' : ''; ?>>Mediana</option>
                                                                <option value="pequeña" <?php echo ($row['tipo_panela'] == 'pequeña') ? 'selected' : ''; ?>>Pequeña</option>
                                                            </select>
                                                            <label for="tipo_panela">Tipo de Panela</label>
                                                        </div>
                                                        <div class="form-floating mb-3">
                                                            <input type="number" class="form-control" name="precio_unitario" value="<?php echo $row['precio_unitario']; ?>" required>
                                                            <label for="precio_unitario">Precio por unidad</label>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Actualizar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay tipos de panela registrados.</td></tr>";
                            }
                            mysqli_close($conexion);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

   <!-- codigo para eliminar los mensajes despues de 10 segundos  -->
    <script>
        // Esperar a que el DOM esté completamente cargado
        document.addEventListener("DOMContentLoaded", function() {
            // Seleccionar el elemento del mensaje
            var mensaje = document.getElementById("mensaje-exito");
            if (mensaje) {
                // Ocultar el mensaje después de 10 segundos (10000 milisegundos)
                setTimeout(function() {
                    mensaje.style.display = "none";
                }, 10000);
            }
        });
    </script>
    
    <!-- para busqueda de personas por nombre -->
    <script>
        function cargarPersonas(pagina = 1, busqueda = '') {
            fetch(`listapersonas.php?pagina=${pagina}&busqueda=${busqueda}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('listado-personas').innerHTML = html;
            });
        }

        // Cargar la primera página al inicio
        cargarPersonas();

        // Buscar personas
        document.getElementById('busqueda').addEventListener('input', function() {
            cargarPersonas(1, this.value);
        });

        // Manejar la paginación de personas
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('paginacion')) {
                e.preventDefault();
                cargarPersonas(e.target.dataset.pagina);
            }
        });
    </script>
    <!-- para cargar los datos al modal editar persona y registrar persona -->
    <script>
        // Función para limpiar el formulario de registro
    function limpiarFormulario() {
        document.getElementById('id_persona').value = '';
        document.getElementById('nombre').value = '';
        document.getElementById('cedula').value = '';
        document.getElementById('telefono').value = '';
        document.getElementById('direccion').value = '';
        
        // Cambiar el título del modal a "Registrar Persona"
        document.getElementById('registroPersonaLabel').textContent = 'Registrar Persona';
    }
    // Escuchar eventos de clic en el documento
    document.addEventListener('click', function(e) {
        // Si se hace clic en un botón de editar
        if (e.target.classList.contains('btn-editar')) {
            const idPersona = e.target.getAttribute('data-id');
            
            // Hacer una petición para obtener los datos de la persona
            fetch(`obtenerpersona.php?id=${idPersona}`)
            .then(response => response.json())
            .then(data => {
                // Rellenar los campos del formulario con los datos obtenidos
                document.getElementById('id_persona').value = data.id_persona;
                document.getElementById('nombre').value = data.nombre;
                document.getElementById('cedula').value = data.cedula;
                document.getElementById('telefono').value = data.telefono;
                document.getElementById('direccion').value = data.direccion;

                // Cambiar el título del modal
                document.getElementById('registroPersonaLabel').textContent = 'Editar Persona';

                // Mostrar la sección del formulario (o abrir modal si lo usas)
                mostrarSeccion('personas-content'); // si es modal, abrirlo aquí
            });
        }
    });

    // Función para manejar el registro o edición
    document.getElementById('registrarPersonaBtn').addEventListener('click', function() {
        var formData = new FormData(document.getElementById('formRegistroPersona'));
        
        var idPersona = document.getElementById('id_persona').value;
        var url = idPersona ? 'editarpersona.php' : 'registropersona.php'; // Cambiar la URL si es edición o registro
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                location.reload();  // Recargar la lista de personas
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Escuchar el evento para mostrar el formulario de registro
    document.getElementById('mostrarRegistroBtn').addEventListener('click', function() {
        limpiarFormulario(); // Limpiar el formulario al mostrar
        mostrarSeccion('personas-content'); // Mostrar sección de registro
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
