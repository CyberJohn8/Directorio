<?php
// Fix para InfinityFree
ini_set("session.save_path", __DIR__ . "/../tmp");
if (!file_exists(__DIR__ . "/../tmp")) {
    mkdir(__DIR__ . "/../tmp", 0777, true);
}

session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="https://cyberjohn.infinityfreeapp.com/Menu/iconos/icon2-8 1.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elegir Método de Búsqueda</title>
    <link rel="stylesheet" href="MenuLiteraturaBiblica.css"> <!-- Asegúrate de que la ruta es correcta -->
    
    

</head>
<body>

    <div class="container">
        <a href="https://cyberjohn.infinityfreeapp.com/Menu/index.php" class="return-button"></a>
        <h2>Selecciona un método de búsqueda</h2>
        
        <div class="submenu">
            <a href="Bliblia/index.php">Leer la Biblia</a>
            <a href="IABiblica/index.php">Consultar IA</a>


            <?php if ($_SESSION["rol"] !== "invitado") : ?>
                <a href="conversacion/index.php">Consultar Chat</a>
        <?php endif; ?>
        </div>
    </div>

</body>
</html>