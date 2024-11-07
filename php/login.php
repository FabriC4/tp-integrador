<?php
session_start();
require 'db_connection.php'; // Incluir archivo de conexión a la base de datos

// Comprobar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['email']; // Asegúrate de que el nombre del campo sea correcto
    $contrasena = $_POST['contrasena'];

    // Consulta a la base de datos
    $query = "SELECT * FROM usuarios WHERE correo = ? LIMIT 1"; // Usar 'correo' en lugar de 'email'
    $stmt = $conn->prepare($query); // Usa la conexión correcta
    $stmt->bind_param('s', $correo); // Vincula el parámetro correo
    $stmt->execute(); // Ejecuta la consulta
    $resultado = $stmt->get_result(); // Obtén el resultado de la consulta
    $usuario = $resultado->fetch_assoc(); // Extrae el usuario como array asociativo

    // Verificar si el correo existe
    if ($usuario) {
        // Verificar la contraseña usando password_verify
        if (password_verify($contrasena, $usuario['contrasena'])) {
            // Crear la sesión
            $_SESSION['usuario'] = $usuario['nombre_usuario'];
            header('Location: ../index.php'); // Redirigir a la página principal
            exit();
        } else {
            // Si la contraseña es incorrecta, redirigir a la página de error
            header("Location: ../pages/contrasena_incorrecta.html");
            exit();
        }
    } else {
        // Si el correo no existe, redirigir al index
        header("Location: ../pages/no_existe_usuario.html");
        exit();
    }
}
?>

