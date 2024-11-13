<?php
include '../php/db_connection.php';
session_start();

// Verificar que el usuario sea admin
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $url = $_POST['url'];
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

    // Verificar la carga del archivo
    if (isset($_FILES['icono']) && $_FILES['icono']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = $_FILES['icono']['name'];
        $rutaTemporal = $_FILES['icono']['tmp_name'];
        $carpetaDestino = '../imagenes/';

        // Crear la carpeta de destino si no existe
        if (!file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        // Definir la ruta completa en la carpeta de destino
        $rutaDestino = $carpetaDestino . basename($nombreArchivo);

        // Mover el archivo a la carpeta destino si aún no está allí
        if (!file_exists($rutaDestino)) {
            if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
                echo "Archivo movido exitosamente a la carpeta destino.<br>";
            } else {
                die("Error al mover el archivo. Verifica los permisos de la carpeta.");
            }
        } else {
            echo "El archivo ya existe en la carpeta destino.<br>";
        }

        // Guardar la ruta en la base de datos (ejemplo: 'imagenes/imagen.jpg')
        $rutaDB = 'imagenes/' . basename($nombreArchivo);

        // Ejecutar la inserción en la tabla correspondiente
        $sql = "INSERT INTO $tabla (nombre, descripcion, url, icono) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $descripcion, $url, $rutaDB);

        if ($stmt->execute()) {
            echo "Enlace añadido con éxito";
        } else {
            echo "Error al añadir el enlace: " . $conn->error;
        }

        $stmt->close();

        // Redirigir al archivo adecuado
        if ($origen === 'index') {
            header("Location: ../index.php");
        } elseif ($origen === 'juegos') {
            header("Location: ../pages/Juegos.php");
        } elseif ($origen === 'estadisticas') {
            header("Location: ../pages/Estadisticas.php");
        }
        exit();
    } else {
        // Mostrar mensaje de error específico para la carga de la imagen
        if (!isset($_FILES['icono'])) {
            echo "El campo 'icono' no fue enviado. Verifica que el formulario tenga enctype='multipart/form-data'.<br>";
        } else {
            echo "Error en la carga de la imagen. Código de error: " . $_FILES['icono']['error'] . "<br>";
        }
        exit(); // Detener el script en caso de error
    }
} else {
    echo "No tienes permisos para añadir enlaces.";
}
?>
