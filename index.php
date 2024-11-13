<?php
// Incluir la conexión a la base de datos y iniciar la sesión
include './php/db_connection.php';
session_start();

// Comprobar si el usuario ha iniciado sesión
$usuario_logueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
$is_admin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'; // Verificar si el usuario es admin

// Consultar los enlaces desde la base de datos
$query = $conn->query("SELECT * FROM enlaces");
$enlaces = [];
if ($query) {
    while ($row = $query->fetch_assoc()) {
        $enlaces[] = $row;
    }
}

/*echo "Rol del usuario: " . $_SESSION['rol']; // Agrega esta línea para depuración*/

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

<main>
    <section name="enlaces-principales">
        <table>
            <tr>
                <th>Enlace</th>
                <th>Descripción</th>
                <?php if ($is_admin): ?><th>Acciones</th><?php endif; ?>
            </tr>
            <?php foreach ($enlaces as $enlace): ?>
                <tr>
                    <td><a href="<?php echo htmlspecialchars($enlace['url']); ?>" class="enlace-con-imagen">
                        <img src="<?php echo htmlspecialchars($enlace['icono']); ?>" alt="Icono" class="icono">
                        <?php echo htmlspecialchars($enlace['nombre']); ?>
                    </a></td>
                    <td><?php echo htmlspecialchars($enlace['descripcion']); ?></td>
                    <?php if ($is_admin): ?>
                        <td>
                            <form method="POST" action="php/eliminar_enlace.php">
                                <input type="hidden" name="id" value="<?php echo $enlace['id']; ?>">
                                <input type="hidden" name="origen" value="index">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($is_admin): ?>
        <!-- Formulario para añadir enlace solo visible para administradores -->
        <form method="POST" action="php/agregar_enlace.php" enctype="multipart/form-data">
    <input type="text" name="nombre" placeholder="Nombre del enlace" required>
    <input type="text" name="descripcion" placeholder="Descripción" required>
    <input type="text" name="url" placeholder="URL" required>
    <input type="file" name="icono" accept="image/*" required> <!-- Campo para seleccionar archivo de imagen -->
    <input type="hidden" name="origen" value="index">
    <button type="submit">Añadir Enlace</button>
</form>
        <?php endif; ?>
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

<script src="./js/script.js" defer></script>
</body>
</html>
