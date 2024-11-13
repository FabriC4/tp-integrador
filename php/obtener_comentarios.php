<?php
include '../php/db_connection.php';
session_start();

$tema = $_POST['tema'];
$usuario_logueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

$sql = "SELECT c.id AS comentario_id, c.comentario, c.fecha, u.nombre_usuario 
        FROM comentarios c 
        JOIN usuarios u ON c.usuario_id = u.id 
        WHERE c.tema = ? ORDER BY c.fecha DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $tema);
$stmt->execute();
$result = $stmt->get_result();

$output = "<h3>Comentarios para el tema: " . htmlspecialchars($tema) . "</h3>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comentario_id = $row['comentario_id'];
        $output .= "<div class='comentario'>
                        <p><strong>" . htmlspecialchars($row['nombre_usuario']) . "</strong>: " . htmlspecialchars($row['comentario']) . "</p>
                        <p>Fecha: " . $row['fecha'] . "</p>";
        
        // Respuestas al comentario
        $sql_respuestas = "SELECT r.id AS respuesta_id, r.respuesta, r.fecha, u.nombre_usuario 
                            FROM respuestas r
                            JOIN usuarios u ON r.usuario_id = u.id
                            WHERE r.comentario_id = ? ORDER BY r.fecha ASC";
        $stmt_respuestas = $conn->prepare($sql_respuestas);
        $stmt_respuestas->bind_param("i", $comentario_id);
        $stmt_respuestas->execute();
        $respuestas = $stmt_respuestas->get_result();

        $output .= "<div class='respuestas' id='respuestas-$comentario_id' style='display:none;'>";
        if ($respuestas->num_rows > 0) {
            while ($respuesta = $respuestas->fetch_assoc()) {
                $output .= "<p><strong>" . htmlspecialchars($respuesta['nombre_usuario']) . "</strong>: " . htmlspecialchars($respuesta['respuesta']) . "</p>";
                $output .= "<p>Fecha: " . $respuesta['fecha'] . "</p>";
            }
        } else {
            $output .= "<p>No hay respuestas para este comentario.</p>";
        }
        $output .= "</div>";
        
        $output .= "<button class='mostrar-respuestas' data-comentario-id='$comentario_id'>Mostrar respuestas</button>";

        $output .= "</div>"; // Cierra el comentario
    }
} else {
    $output .= "<p>No hay comentarios en este tema.</p>";
}

echo $output;
?>
