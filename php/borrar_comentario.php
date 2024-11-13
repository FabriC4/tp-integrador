<?php
session_start();
include '../php/db_connection.php'; // Asegúrate de que este archivo se incluya para la conexión

// Verificar si el usuario está logueado y se pasa el ID del comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario_id']) && isset($_SESSION['usuario'])) {
    $comentario_id = $_POST['comentario_id'];
    $usuario_logueado = $_SESSION['usuario'];

    // Validar que el usuario es el dueño del comentario
    $sql = "SELECT c.id, u.nombre_usuario 
            FROM comentarios c
            JOIN usuarios u ON c.usuario_id = u.id
            WHERE c.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $comentario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $comentario = $result->fetch_assoc();
        
        // Verificar si el usuario logueado es el dueño del comentario
        if ($usuario_logueado == $comentario['nombre_usuario']) {
            
            // Eliminar las respuestas de la tabla respuestas_eliminadas antes de borrar el comentario
            $delete_respuestas_eliminadas_sql = "DELETE FROM respuestas_eliminadas WHERE comentario_id = ?";
            $delete_respuestas_eliminadas_stmt = $conn->prepare($delete_respuestas_eliminadas_sql);
            $delete_respuestas_eliminadas_stmt->bind_param("i", $comentario_id);
            $delete_respuestas_eliminadas_stmt->execute();

            // Eliminar primero las respuestas asociadas al comentario antes de eliminar el comentario
            $delete_respuestas_sql = "DELETE FROM respuestas WHERE comentario_id = ?";
            $delete_respuestas_stmt = $conn->prepare($delete_respuestas_sql);
            $delete_respuestas_stmt->bind_param("i", $comentario_id);

            if ($delete_respuestas_stmt->execute()) {
                // Después de eliminar las respuestas, eliminar el comentario
                $delete_comentario_sql = "DELETE FROM comentarios WHERE id = ?";
                $delete_comentario_stmt = $conn->prepare($delete_comentario_sql);
                $delete_comentario_stmt->bind_param("i", $comentario_id);

                if ($delete_comentario_stmt->execute()) {
                    header("Location: ../pages/Foro.php"); // Redirige al foro después de borrar el comentario
                    exit();
                } else {
                    echo "Error al borrar el comentario: " . $conn->error;
                }
            } else {
                echo "Error al borrar las respuestas: " . $conn->error;
            }
        } else {
            echo "No tienes permiso para borrar este comentario.";
        }
    } else {
        echo "Comentario no encontrado.";
    }
} else {
    echo "ID de comentario no válido o no estás logueado.";
}
?>
