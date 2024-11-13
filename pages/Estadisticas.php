<?php
// Incluir la conexión a la base de datos y iniciar la sesión
include '../php/db_connection.php';
session_start();

// Comprobar si el usuario ha iniciado sesión
$usuario_logueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
$is_admin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'; // Verificar si el usuario es admin

// Consultar los enlaces desde la tabla enlaces_estadisticas
$query = $conn->query("SELECT * FROM enlaces_estadisticas");
$enlaces = [];
if ($query) {
    while ($row = $query->fetch_assoc()) {
        $enlaces[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Estadísticas de Fútbol</title>
</head>

<body>
<header class="rectangulo-transparente">
    <section class="header-container">
        <div class="logo-titulo-container">
            <img src="../imagenes/ballgif.gif" alt="Logo" class="logo">
            <h1 class="titulo-con-marco">Estadísticas</h1>
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
        <p>Toda la información y actualidad sobre este deporte</p>
    </nav>

    <main>
        <section name="estadisticas-enlaces">
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
                                <form method="POST" action="../php/eliminar_enlace.php">
                                    <input type="hidden" name="id" value="<?php echo $enlace['id']; ?>">
                                    <input type="hidden" name="origen" value="estadisticas">
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </table>

            <?php if ($is_admin): ?>
            <!-- Formulario para añadir enlace solo visible para administradores -->
            <form method="POST" action="../php/agregar_enlace.php" class="form-agregar-enlace">
                <input type="text" name="nombre" placeholder="Nombre del enlace" required>
                <input type="text" name="descripcion" placeholder="Descripción" required>
                <input type="text" name="url" placeholder="URL" required>
                <input type="text" name="icono" placeholder="Ruta del icono" required>
                <input type="hidden" name="origen" value="estadisticas">
                <button type="submit">Añadir Enlace</button>
            </form>
            <?php endif; ?>
        </section>

    <footer>
        <a href="../index.php">Volver al inicio</a>
        <button id="theme-toggle">Cambiar a Tema Oscuro</button>
    </footer>

    </main>


    <script src="../js/script.js" defer></script>
</body>
</html>
