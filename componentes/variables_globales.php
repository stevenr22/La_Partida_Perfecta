<?php
require_once "../db/conexion.php";
session_start();

function obtenerUsuarioSesion() {
    global $conn; // Usamos la conexión de conexion.php

    if (!isset($_SESSION['usuario_id'])) {
        return null;
    }
    $usuarioId = $_SESSION['usuario_id'];

    // Consulta directa
    $sql = "SELECT 
                u.id_usu, 
                u.nombre_usu, 
                u.apellido_usu, 
                u.usuario_usu, 
                r.id_rol,
                
                r.nombre_rol
            FROM usuario AS u
            JOIN rol AS r ON u.id_rol = r.id_rol
            WHERE u.id_usu = '$usuarioId'";

    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        return $resultado->fetch_assoc(); // Devuelve datos como array asociativo
    }

    return null; // Si no encuentra resultados
}
