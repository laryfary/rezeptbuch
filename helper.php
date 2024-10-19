<?php

	global $path_up;

	session_start();

    require_once $path_up.'engine/vendor/autoload.php';
    if (isset($_GET["logout"])) {

session_destroy();

unset($_SESSION["name"]);

header("Location: /rezeptbuch");
}

    $dblink = connect_db();

    function connect_db() {

        $mysqli = new mysqli("localhost", "root", "", "test");

        return $mysqli;
    }

    function get_template($name,$data) {

	    $loader = new \Twig\Loader\FilesystemLoader($path_up.'view/templates');

	    $twig = new \Twig\Environment($loader, []);

	    $template = $twig->load($name.'.html');

	    return $template->render($data);
    }
?>