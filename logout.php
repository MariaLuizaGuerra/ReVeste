<?php
    session_start();

    //Limpar os dados da sessão
    $_SESSION = [];

    //Destruir os cookies
    if(ini_get("session.use_cookies")){
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
    }

    //Destruir a sessão
    session_destroy();

    //Redirecionar para a tela de login
    header("Location: login.php");
    exit;


?>