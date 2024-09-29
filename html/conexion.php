<?php
$host = "localhost";
$user = "root";
$password = "Amaurys2003*";
$bd = "produccionpanela";

$conexion = mysqli_connect($host,$user,$password,$bd);

if (!$conexion) {
    die("problema con la conexion".mysqli_connect_error());
}

?>