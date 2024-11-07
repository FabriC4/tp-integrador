<?php
// Iniciar sesión
session_start();

// Comprobar si el usuario ha iniciado sesión
$usuario_logueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

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
    <p>Discusiones y debates sobre este gran deporte</p>
</nav>

<main>
    <section class="recent-topics">
        <h2>Temas Recientes</h2>
        <ul>
            <?php
            // Temas (partidos)
            $temas = ["River vs Talleres", "San Lorenzo vs Atlético Mineiro", "Boca vs Cruzeiro", "Racing vs Huachipato"];
            foreach ($temas as $tema) {
                echo "<li><img src='../imagenes/libertadores.png' alt='Logo' class='icono'>$tema</li>";

                // Mostrar los comentarios para este tema
                $sql = "SELECT c.id AS comentario_id, c.comentario, c.fecha, u.nombre_usuario 
                        FROM comentarios c 
                        JOIN usuarios u ON c.usuario_id = u.id 
                        WHERE c.tema = '$tema' ORDER BY c.fecha DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='comentario'>
                                <p><strong>" . htmlspecialchars($row['nombre_usuario']) . "</strong>: " . htmlspecialchars($row['comentario']) . "</p>
                                <p>Fecha: " . $row['fecha'] . "</p>";
                    
                        // Mostrar las respuestas a este comentario
$comentario_id = $row['comentario_id'];
$sql_respuestas = "SELECT r.id AS respuesta_id, r.respuesta, r.fecha, u.nombre_usuario 
                    FROM respuestas r
                    JOIN usuarios u ON r.usuario_id = u.id
                    WHERE r.comentario_id = '$comentario_id'
                    ORDER BY r.fecha ASC";
$respuestas = $conn->query($sql_respuestas);

if ($respuestas->num_rows > 0) {
    echo "<div class='respuestas' id='respuestas-$comentario_id' style='display:none;'>"; // Inicia ocultando las respuestas
    while ($respuesta = $respuestas->fetch_assoc()) {
        echo "<p><strong>" . htmlspecialchars($respuesta['nombre_usuario']) . "</strong>: " . htmlspecialchars($respuesta['respuesta']) . "</p>";
        echo "<p>Fecha: " . $respuesta['fecha'] . "</p>";
        
        // Si el usuario logueado es el autor de la respuesta, mostrar el botón para borrar
        if ($usuario_logueado == $respuesta['nombre_usuario']) {
            echo "<form method='POST' action='../php/borrar_respuesta.php'>
                    <input type='hidden' name='respuesta_id' value='" . $respuesta['respuesta_id'] . "'>
                    <button type='submit'>Borrar Respuesta</button>
                  </form>";
        }
    }
    echo "</div>"; // Cierra el div de respuestas

    // Botón para alternar visibilidad de las respuestas
    echo "<button class='mostrar-respuestas' data-comentario-id='$comentario_id'>Mostrar respuestas</button>";
} else {
    echo "<p>No hay respuestas para este comentario.</p>";
}

                    
                        // Formulario para responder al comentario
                        if ($usuario_logueado) {
                            echo "<form method='POST' action='../php/respuesta.php'>
                                    <input type='hidden' name='comentario_id' value='" . $comentario_id . "'>
                                    <input type='text' name='respuesta' placeholder='Escribe una respuesta...'>
                                    <button type='submit'>Responder</button>
                                  </form>";
                        }
                    
                        // Botón para borrar comentario (solo el autor)
                        if ($usuario_logueado == $row['nombre_usuario']) {
                            echo "<form method='POST' action='../php/borrar_comentario.php'>
                                    <input type='hidden' name='comentario_id' value='" . $comentario_id . "'>
                                    <button type='submit'>Borrar Comentario</button>
                                  </form>";
                        }
                    
                        echo "</div>"; // Cierra el div de comentarios
                    }
                } else {
                    echo "<p>No hay comentarios en este tema.</p>";
                }

                // Formulario para agregar comentario si está logueado
                if ($usuario_logueado) {
                    echo "<form method='POST' action='../php/comentario.php'>
                            <input type='hidden' name='tema' value='$tema'>
                            <textarea name='comentario' placeholder='Escribe tu comentario...'></textarea>
                            <button type='submit'>Comentar</button>
                          </form>";
                }
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
