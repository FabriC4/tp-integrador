<?php
session_start();
include '../php/db_connection.php'; // Asegúrate de que este archivo se incluya para la conexión

// Verificar si el usuario está logueado y se pasa el ID de la respuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['respuesta_id']) && isset($_SESSION['usuario'])) {
    $respuesta_id = $_POST['respuesta_id'];
    $usuario_logueado = $_SESSION['usuario'];

    // Validar que el usuario es el dueño de la respuesta
    $sql = "SELECT r.id, r.usuario_id, u.nombre_usuario 
            FROM respuestas r
            JOIN usuarios u ON r.usuario_id = u.id
            WHERE r.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $respuesta_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $respuesta = $result->fetch_assoc();
        
        // Verificar si el usuario logueado es el dueño de la respuesta
        if ($usuario_logueado == $respuesta['nombre_usuario']) {
            // Eliminar la respuesta
            $delete_sql = "DELETE FROM respuestas WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $respuesta_id);

            if ($delete_stmt->execute()) {
                header("Location: ../pages/Foro.php"); // Redirige al foro después de borrar la respuesta
                exit();
            } else {
                echo "Error al borrar la respuesta: " . $conn->error;
            }
        } else {
            echo "No tienes permiso para borrar esta respuesta.";
        }
    } else {
        echo "Respuesta no encontrada.";
    }
} else {
    echo "ID de respuesta no válido o no estás logueado.";
}
?>
