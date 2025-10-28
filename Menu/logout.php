<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Si existe una cookie de sesión, eliminarla
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();

// Redirigir al login
header("Location: https://cyberjohn.infinityfreeapp.com");
exit();
?>


////<?php
//session_start();
//session_unset();
//session_destroy();
//header("Location: https://cyberjohn.infinityfreeapp.com/");
//exit();
/*?>/**/
