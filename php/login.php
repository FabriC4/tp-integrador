<?php
require 'db_connection.php'; // Incluir archivo de conexión a la base de datos
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    // Consulta a la base de datos para obtener el usuario y el rol
    $query = "SELECT * FROM usuarios WHERE correo = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    // Verificar si el correo existe
    if ($usuario) {
        // Verificar la contraseña usando password_verify
        if (password_verify($contrasena, $usuario['contrasena'])) {
            // Guardar la información del usuario en la sesión
            $_SESSION['usuario'] = $usuario['nombre_usuario'];
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['rol'] = $usuario['rol']; // Asegúrate de guardar el rol del usuario aquí

            // Redirigir a la página principal
            header('Location: ../index.php');
            exit();
        } else {
            header("Location: ../pages/contrasena_incorrecta.html");
            exit();
        }
    } else {
        header("Location: ../pages/no_existe_usuario.html");
        exit();
    }
}
?>
