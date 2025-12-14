<?php
require_once("../db/conexion.php");
session_start();

// ========================
// 1. Recibir datos
// ========================
$id_usu         = trim($_POST["id_usu"]);
$nombre_usu     = trim($_POST["nombre"]);
$apellido_usu   = trim($_POST["apellido"]);
$usuario        = trim($_POST["usuario"]);

// ========================
// 2. Validación básica
// ========================
if (
    $id_usu === "" ||
    $nombre_usu === "" ||
    $apellido_usu === "" ||
    $usuario === ""
) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Todos los campos son obligatorios."
    ]);
    exit;
}



// ===================================
// 3. Verificar si el usuario ya existe
// ===================================
$sqlUsuario = "SELECT id_usu 
               FROM usuario 
               WHERE usuario_usu = '$usuario' 
               AND id_usu != '$id_usu'";
$resUsuario = $conn->query($sqlUsuario);

if ($resUsuario && $resUsuario->num_rows > 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "El nombre de usuario ya está registrado por otro usuario."
    ]);
    exit;
}

// ============================================
// 5. UPDATE (SIEMPRE CAMBIA CONTRASEÑA)
// ============================================
$sql = "UPDATE usuario SET 
            nombre_usu      = '$nombre_usu',
            apellido_usu    = '$apellido_usu',
            usuario_usu     = '$usuario'
            WHERE id_usu = '$id_usu'";

// ============================================
// 6. Ejecutar UPDATE
// ============================================
if ($conn->query($sql)) {

    echo json_encode([
        "ok" => true,
        "mensaje" => "Perfil actualizado correctamente."
    ]);

} else {

    echo json_encode([
        "ok" => false,
        "mensaje" => "Error al actualizar el perfil."
    ]);
}
exit;
