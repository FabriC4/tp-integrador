<?php
// Iniciar sesión y comprobar si el usuario es administrador
session_start();
$usuario_logueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
$is_admin = $usuario_logueado === 'admin'; // Ajusta esto según cómo identificas al admin

if (!$is_admin) {
    header("Location: ../index.php"); // Redirecciona a la página principal si no es admin
    exit;
}

include '../php/db_connection.php';

// Agregar nuevo tema
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuevo_tema'])) {
    $nuevo_tema = $_POST['nuevo_tema'];
    $sql = "INSERT INTO temas (titulo) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nuevo_tema);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_temas.php"); // Refresca la página
}

// Eliminar tema
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tema_id'])) {
    $tema_id = $_POST['tema_id'];
    $sql = "DELETE FROM temas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tema_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_temas.php");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Temas</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<nav><h2>Administración de Temas</h2></nav>

<main><!-- Formulario para agregar un nuevo tema -->
<form method="POST" action="admin_temas.php">
    <label for="nuevo_tema">Nuevo Tema:</label>
    <input type="text" name="nuevo_tema" required>
    <button type="submit">Agregar Tema</button>
</form>

<!-- Lista de temas con opción para eliminarlos -->
<h3>Temas Actuales</h3>
<ul>
    <?php
    $sql = "SELECT * FROM temas";
    $result = $conn->query($sql);
    while ($tema = $result->fetch_assoc()) {
        echo "<li>{$tema['titulo']}
                <form method='POST' action='admin_temas.php' style='display:inline;'>
                    <input type='hidden' name='tema_id' value='{$tema['id']}'>
                    <button type='submit'>Eliminar</button>
                </form>
              </li>";
    }
    ?>
</ul>

<a href="../index.php">Volver al inicio</a></main>

</body>
</html>
