<?php
require_once("../db/conexion.php");
session_start();

// ========================
// 1. Recibir datos
// ========================
$id_usu = trim($_POST["id_usu"]);
$nombre_usu  = trim($_POST["nombre"]);
$apellido_usu = trim($_POST["apellido"]);
$email_usu    = trim($_POST["email"]);
$usuario      = trim($_POST["usuario"]);
$nuevaContrasena = trim($_POST["contrasena"]);

// ========================
// 2. Validación
// ========================
if ($id_usu === "" || $nombre_usu === "" || $apellido_usu === "" || $email_usu === "" || $usuario === "") {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Todos los campos son obligatorios."
    ]);
    exit;
}

// ===================================
// 3. Verificar si el email ya existe
// ===================================
$sqlEmail = "SELECT id_usu FROM usuario 
             WHERE email_usu = '$email_usu' AND id_usu != '$id_usu'";
$resEmail = $conn->query($sqlEmail);

if ($resEmail && $resEmail->num_rows > 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "El correo ya está registrado por otro usuario."
    ]);
    exit;
}

// ===================================
// 4. Verificar si el usuario ya existe
// ===================================
$sqlUsuario = "SELECT id_usu FROM usuario 
               WHERE usuario_usu = '$usuario' AND id_usu != '$id_usu'";
$resUsuario = $conn->query($sqlUsuario);

if ($resUsuario && $resUsuario->num_rows > 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "El nombre de usuario ya está registrado por otro usuario."
    ]);
    exit;
}

// ============================================
// 5. Crear UPDATE según si cambia contraseña
// ============================================
if ($nuevaContrasena === "") {

    // Sin cambiar contraseña
    $sql = "UPDATE usuario SET 
                nombre_usu = '$nombre_usu',
                apellido_usu = '$apellido_usu',
                email_usu = '$email_usu',
                usuario_usu = '$usuario'
            WHERE id_usu = '$id_usu'";
} else {

    // Cambia contraseña
    $sql = "UPDATE usuario SET 
                nombre_usu = '$nombre_usu',
                apellido_usu = '$apellido_usu',
                email_usu = '$email_usu',
                usuario_usu = '$usuario',
                contrasena_usu = '$nuevaContrasena'
            WHERE id_usu = '$id_usu'";
}

// ============================================
// 6. Ejecutar UPDATE
// ============================================
if ($conn->query($sql)) {

    // Actualizar variables de sesión para que se reflejen en el perfil
    // $_SESSION["nombre_usu"] = $nombre_usu;
    // $_SESSION["apellido_usu"] = $apellido_usu;
    // $_SESSION["email_usu"] = $email_usu;
    // $_SESSION["usuario_usu"] = $usuario;

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
