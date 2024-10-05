<?php
include 'conexion.php';

$busqueda = $_GET['busqueda'] ?? '';
$pagina = $_GET['pagina'] ?? 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

// Consulta con búsqueda y paginación
$sql = "SELECT * FROM Persona WHERE nombre LIKE ? ORDER BY nombre ASC LIMIT ?, ?";
$stmt = mysqli_prepare($conexion, $sql);
$busqueda_param = "%" . $busqueda . "%";
mysqli_stmt_bind_param($stmt, "sii", $busqueda_param, $offset, $limite);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

echo "<table class='table table-bordered'>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Teléfono</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>";
while ($fila = mysqli_fetch_assoc($resultado)) {
    echo '<tr>
        <td>' . htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8') . '</td>
        <td>' . htmlspecialchars($fila['cedula'], ENT_QUOTES, 'UTF-8') . '</td>
        <td>' . htmlspecialchars($fila['telefono'], ENT_QUOTES, 'UTF-8') . '</td>
        <td>
            <button data-bs-toggle="modal" data-bs-target="#registroPersonaModal" class="btn btn-warning btn-editar" data-id="' . htmlspecialchars($fila['id_persona'], ENT_QUOTES, 'UTF-8') . '">
                Editar
            </button>
        </td>
      </tr>';
}
echo "</tbody>
    </table>";

// Paginar
$sql_total = "SELECT COUNT(*) as total FROM Persona WHERE nombre LIKE ?";
$stmt_total = mysqli_prepare($conexion, $sql_total);
mysqli_stmt_bind_param($stmt_total, "s", $busqueda_param);
mysqli_stmt_execute($stmt_total);
$total = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_total))['total'];
$total_paginas = ceil($total / $limite);

for ($i = 1; $i <= $total_paginas; $i++) {
    echo "<a href='#' class='btn btn-primary paginacion' data-pagina='$i'>$i</a> ";
}

mysqli_close($conexion);
?>
