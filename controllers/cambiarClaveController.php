<?php
require "../db/conexion.php";
session_start();

$cedula = trim($_POST["cedula"]);
$nueva_clave = trim($_POST["nueva_clave"]);

if ($cedula === "" || $nueva_clave === "") {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Debe ingresar la nueva contraseña."
    ]);
    exit;
}



// ===============================
// ACTUALIZAR CONTRASEÑA
// ===============================
$sql = "UPDATE usuario
        SET contrasena_usu = '$nueva_clave'
        WHERE cedula_usu = '$cedula'";

if ($conn->query($sql)) {

    if ($conn->affected_rows > 0) {
        echo json_encode([
            "ok" => true,
            "mensaje" => "Contraseña actualizada."
        ]);
    } else {
        echo json_encode([
            "ok" => false,
            "mensaje" => "No se encontró el usuario."
        ]);
    }

} else {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Error al actualizar la contraseña."
    ]);
}

exit;
