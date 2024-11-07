<?php
// Iniciar sesión
session_start();

// Comprobar si el usuario ha iniciado sesión
$usuario_logueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Metadatos básicos de la página -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">s
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Título de la página -->
    <title>Futbol Hub</title>
</head>

<body>
    <header class="rectangulo-transparente">
        <section class="header-container">
            <div class="logo-titulo-container">
                <img src="imagenes/ballgif.gif" alt="Logo" class="logo">
                <h1 class="titulo-con-marco">Futbol Hub</h1>
            </div>

            <!-- Mostrar formulario de login si no está logueado -->
            <?php if (!$usuario_logueado) : ?>
            <div class="login-container" id="login-container">
                <form id="login-form" method="POST" action="php/login.php">
                    <input type="email" name="email" id="email-input" placeholder="Ingrese su correo electrónico" required>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" required>
                    <input type="submit" value="Iniciar sesión">
                    <a href="pages/register.html">Registrarse</a>
                </form>
            </div>
            <?php else : ?>
            <!-- Mostrar mensaje de bienvenida si el usuario está logueado -->
            <div class="bienvenida-container" id="bienvenida-container">
                <p>Bienvenido, <span id="correo-bienvenida"><?php echo htmlspecialchars($usuario_logueado); ?></span></p>
            </div>
            <form method="POST" action="php/logout.php">
                    <button type="submit" id="logout-btn">Cerrar sesión</button>
            </form>
            <?php endif; ?>
        </section>
    </header>

    <nav>
        <p>Tu fuente para todo lo relacionado con el futbol.</p>
    </nav>

    <<main>
        <section name="enlaces-principales">
            <table>
                <tr>
                    <th>Enlace</th>
                    <th>Descripción</th>
                </tr>
                <tr>
                    <td><a href="pages/Estadisticas.html" class="enlace-con-imagen"><img src="imagenes/giphy.gif"
                                alt="Balón" class="icono"> Estadísticas</a></td>
                    <td>Consulta las estadísticas más recientes de los partidos.</td>
                </tr>
                <tr>
                    <td><a href="pages/Juegos.html" class="enlace-con-imagen"><img src="imagenes/giphy.gif" alt="Balón"
                                class="icono"> Juegos de Fútbol</a></td>
                    <td>Juega y disfruta de los mejores juegos de fútbol.</td>
                </tr>
                <tr>
                    <td><a href="pages/Foro.php" class="enlace-con-imagen"><img src="imagenes/giphy.gif" alt="Balón"
                                class="icono"> Foro</a></td>
                    <td>Participa en las discusiones y comparte tus opiniones.</td>
                </tr>
                <tr>
                    <td><a href="pages/The_Goats.html" class="enlace-con-imagen"><img src="imagenes/Goat.gif"
                                alt="Balón" class="icono"> The Goats</a></td>
                    <td>Descubre a los mejores jugadores de todos los tiempos.</td>
                </tr>
            </table>
        </section>
    </main>

    <section id="simulador-partido">
        <h2>Simulador de Partido</h2>
        <form id="team-form">
            <label for="teamA">Equipo A:</label>
            <input type="text" id="teamA" placeholder="Equipo Local" required>
            <br>
            <label for="teamB">Equipo B:</label>
            <input type="text" id="teamB" placeholder="Equipo Visitante" required>
            <br>
            <button type="submit">Simular Resultado</button>
        </form>
        <div id="resultado-partido"></div>
    </section>

    <footer>
        <button id="theme-toggle">Cambiar Tema</button>
    </footer>

    <script src="./js/script.js" defear></script>
</body>

</html>
