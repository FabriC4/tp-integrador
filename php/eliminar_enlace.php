<?php
include '../php/db_connection.php';
session_start();

// Verificar que el usuario sea admin
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    $id = $_POST['id'];
    $origen = $_POST['origen']; // Identificar si el origen es 'index', 'juegos', o 'estadisticas'

    // Definir la tabla en función del origen
    if ($origen === 'index') {
        $tabla = 'enlaces';
    } elseif ($origen === 'juegos') {
        $tabla = 'enlaces_juegos';
    } elseif ($origen === 'estadisticas') {
        $tabla = 'enlaces_estadisticas';
    } else {
        die("Origen no válido");
    }

    // Obtener la ruta del archivo de imagen antes de eliminar el registro
    $sql_select = "SELECT icono FROM $tabla WHERE id = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $stmt_select->bind_result($rutaIcono);
    $stmt_select->fetch();
    $stmt_select->close();

    // Eliminar el registro de la tabla correspondiente
    $sql_delete = "DELETE FROM $tabla WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id);
    $stmt_delete->execute();
    $stmt_delete->close();

    // Eliminar el archivo de imagen si existe
    if ($rutaIcono && file_exists("../" . $rutaIcono)) {
        unlink("../" . $rutaIcono);
    }

    // Redirigir al archivo adecuado
    if ($origen === 'index') {
        header("Location: ../index.php");
    } elseif ($origen === 'juegos') {
        header("Location: ../pages/juegos.php");
    } elseif ($origen === 'estadisticas') {
        header("Location: ../pages/Estadisticas.php");
    }
    exit();
} else {
    echo "No tienes permisos para eliminar enlaces.";
}
?>
