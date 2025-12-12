<?php
require_once "../db/conexion.php";
session_start();

function obtenerUsuarioSesion() {
    global $conn; // Usamos la conexión de conexion.php

    $usuarioId = $_SESSION['usuario_id']; // asumimos que siempre está definido

    // Consulta directa
    $sql = "SELECT 
                u.id_usu, 
                u.nombre_usu, 
                u.apellido_usu, 
                u.usuario_usu, 
                u.email_usu,
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
