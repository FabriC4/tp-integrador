<?php
// Iniciar sesión
session_start();

// Comprobar si el usuario ha iniciado sesión
$usuario_logueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

// Identificar al administrador
$is_admin = ($usuario_logueado === 'admin'); // Cambia 'admin' al nombre o rol que usas para el administrador

// Incluir conexión a la base de datos
include '../php/db_connection.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Foro de Fútbol</title>
</head>

<body>
<header class="rectangulo-transparente">
    <section class="header-container">
        <div class="logo-titulo-container">
            <img src="../imagenes/ballgif.gif" alt="Logo" class="logo">
            <h1 class="titulo-con-marco">Foro</h1>
        </div>

        <?php if (!$usuario_logueado) : ?>
        <div class="login-container" id="login-container">
            <form id="login-form" method="POST" action="../php/login.php">
                <input type="email" name="email" id="email-input" placeholder="Ingrese su correo electrónico" required>
                <input type="password" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" required>
                <input type="submit" value="Iniciar sesión">
                <a href="../pages/register.html">Registrarse</a>
            </form>
        </div>
        <?php else : ?>
        <div class="bienvenida-container" id="bienvenida-container">
            <p>Bienvenido, <span id="correo-bienvenida"><?php echo htmlspecialchars($usuario_logueado); ?></span></p>
        </div>
        <form method="POST" action="../php/logout.php">
            <button type="submit" id="logout-btn">Cerrar sesión</button>
        </form>
        <?php endif; ?>
    </section>
</header>

<nav>
    <div>
        <p>Discusiones y debates sobre este gran deporte</p>
        <?php if ($is_admin) : ?>
            <a href="../php/admin_temas.php" class="admin-link">Administrar Temas</a>
        <?php endif; ?>
    </div>
</nav>

<main>
    <section class="recent-topics">
        <h2>Temas Recientes</h2>
        <ul>
            <?php
            $sql_temas = "SELECT titulo FROM temas";
            $result_temas = $conn->query($sql_temas);

            if ($result_temas->num_rows > 0) {
                while ($tema_row = $result_temas->fetch_assoc()) {
                    $tema = $tema_row['titulo'];
                    echo "<li><img src='../imagenes/foro-logo.gif' alt='Logo' class='icono'>" . htmlspecialchars($tema) . "</li>";

                    $sql_comentarios = "SELECT c.id AS comentario_id, c.comentario, c.fecha, u.nombre_usuario 
                                        FROM comentarios c 
                                        JOIN usuarios u ON c.usuario_id = u.id 
                                        WHERE c.tema = '$tema' ORDER BY c.fecha DESC";
                    $result_comentarios = $conn->query($sql_comentarios);

                    if ($result_comentarios->num_rows > 0) {
                        while ($comentario = $result_comentarios->fetch_assoc()) {
                            echo "<div class='comentario'>
                                    <p><strong>" . htmlspecialchars($comentario['nombre_usuario']) . "</strong>: " . htmlspecialchars($comentario['comentario']) . "</p>
                                    <p>Fecha: " . $comentario['fecha'] . "</p>";
                        
                            $comentario_id = $comentario['comentario_id'];
                            $sql_respuestas = "SELECT r.id AS respuesta_id, r.respuesta, r.fecha, u.nombre_usuario 
                                                FROM respuestas r
                                                JOIN usuarios u ON r.usuario_id = u.id
                                                WHERE r.comentario_id = '$comentario_id'
                                                ORDER BY r.fecha ASC";
                            $respuestas = $conn->query($sql_respuestas);

                            if ($respuestas->num_rows > 0) {
                                echo "<div class='respuestas' id='respuestas-$comentario_id' style='display:none;'>";
                                while ($respuesta = $respuestas->fetch_assoc()) {
                                    echo "<p><strong>" . htmlspecialchars($respuesta['nombre_usuario']) . "</strong>: " . htmlspecialchars($respuesta['respuesta']) . "</p>";
                                    echo "<p>Fecha: " . $respuesta['fecha'] . "</p>";
                                    
                                    if ($usuario_logueado == $respuesta['nombre_usuario'] || $is_admin) {
                                        echo "<form method='POST' action='../php/borrar_respuesta.php'>
                                                <input type='hidden' name='respuesta_id' value='" . $respuesta['respuesta_id'] . "'>
                                                <button type='submit'>Borrar Respuesta</button>
                                              </form>";
                                    }
                                }
                                echo "</div>";
                                echo "<button class='mostrar-respuestas' data-comentario-id='$comentario_id'>Mostrar respuestas</button>";
                            } else {
                                echo "<p>No hay respuestas para este comentario.</p>";
                            }

                            if ($usuario_logueado) {
                                echo "<form method='POST' action='../php/respuesta.php'>
                                        <input type='hidden' name='comentario_id' value='$comentario_id'>
                                        <input type='text' name='respuesta' placeholder='Escribe una respuesta...'>
                                        <button type='submit'>Responder</button>
                                      </form>";
                            }
                            
                            if ($usuario_logueado == $comentario['nombre_usuario'] || $is_admin) {
                                echo "<form method='POST' action='../php/borrar_comentario.php'>
                                        <input type='hidden' name='comentario_id' value='$comentario_id'>
                                        <button type='submit'>Borrar Comentario</button>
                                      </form>";
                            }
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No hay comentarios en este tema.</p>";
                    }

                    if ($usuario_logueado) {
                        echo "<form method='POST' action='../php/comentario.php'>
                                <input type='hidden' name='tema' value='$tema'>
                                <textarea name='comentario' placeholder='Escribe tu comentario...'></textarea>
                                <button type='submit'>Comentar</button>
                              </form>";
                    }
                }
            } else {
                echo "<p>No hay temas disponibles.</p>";
            }
            ?>
        </ul>
    </section>

    <footer>
        <a href="../index.php">Volver al inicio</a>
        <button id="theme-toggle">Cambiar Tema</button>
    </footer>
</main>
<script src="../js/script.js"></script>
<script src="../js/foro-script.js"></script>

</body>
</html>
