<?php
// Iniciar sesión
session_start();

// Comprobar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    echo "Debes estar logueado para responder.";
    exit();
}

// Conectar a la base de datos
include 'db_connection.php';

// Recoger los datos del formulario
$comentario_id = isset($_POST['comentario_id']) ? $_POST['comentario_id'] : null;
$respuesta = isset($_POST['respuesta']) ? $_POST['respuesta'] : null;
$usuario_logueado = $_SESSION['usuario'];

// Verificar que se haya enviado una respuesta y un comentario_id
if ($comentario_id && $respuesta) {
    $sql = "INSERT INTO respuestas (comentario_id, respuesta, usuario_id, fecha)
            VALUES (?, ?, (SELECT id FROM usuarios WHERE nombre_usuario = ?), NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $comentario_id, $respuesta, $usuario_logueado);

    if ($stmt->execute()) {
        echo "Respuesta agregada correctamente.";
        header("Location: ../pages/foro.php"); // Redirige de vuelta al foro después de responder
    } else {
        echo "Error al agregar la respuesta: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Faltan datos para procesar la respuesta.";
}

$conn->close();
?>
