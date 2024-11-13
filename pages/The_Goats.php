<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The GOATS</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="ronaldo-messi-page">

    <video autoplay muted loop id="background-video">
        <source src="../imagenes/videoplayback.mp4" type="video/mp4">
    </video>

    <header>
         <div class="header-container-especial">
            <h1 class="titulo-con-marco-especial">RM</h1>
        </div>
    </header>

    <main>
        <section class="estadisticas-jugador golden-black">
            <div class="player" id="messi">
                <a href="players/messi.html">
                    <img src="../imagenes/messi.jpg" alt="Lionel Messi">
                    <h2>Lionel Messi</h2>
                </a>
            </div>
            <div class="player" id="cristiano">
                <a href="players/ronaldo.html">
                    <img src="../imagenes/ronaldo.jpg" alt="Cristiano Ronaldo">
                    <h2>Cristiano Ronaldo</h2>
                </a>
            </div>
        </section>

        <footer class="golden-black-link">
            <p><a href="../index.php">Volver al inicio</a></p>
        </footer>
    </main>

    <!-- Aquí enlazamos el archivo hover.js -->
    <script src="../js/script.js"></script> <!-- Ajusta la ruta del archivo -->
</body>

</html>
