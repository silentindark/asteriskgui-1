<?php

/**
 * Asterisk GUI
 *
 * This is the Javascript / HTML / CSS / Ajax related to asterisk app.
 * Only for viewing settings and conditions, not to configure.
 * Screenshot from real system with Elastix in folder "screenshot". *
 * Using AMI for request info about channels and mysql for CDR stat.
 *
 * @author    Zheltov Anton (anton.zheltov@gmail.com)
 * @license    ☺ License GNU3
 *
 */

use app\models\AuthClass;

require_once "./vendor/autoload.php";
require_once './db/config.php';

session_start(); //Запускаем сессии

/** @var string $page */
$page = '';
$auth = new AuthClass();

if (isset($_POST["login"]) && isset($_POST["password"])) { //Если логин и пароль были отправлены
    if (!$auth->auth($_POST["login"], $_POST["password"])) { //Если логин и пароль введен не правильно
        echo "<h2 style=\"color:red;\">Wrong auth data!</h2>";
    }
}

if (isset($_GET["is_exit"])) { //Если нажата кнопка выхода
    if ($_GET["is_exit"] == 1) {
        $auth->logout(); //Выходим
        header("Location: ?is_exit=0"); //Редирект после выхода
    }
}

if (!$auth->isAuth()) {
    $page = include './view/auth.login.php';
    die($page);
}


if (empty($_GET["p"])) { // if some page requested, then show it, else show page CDR
    $page = 'rep.cdr';
} else {
    $page = $_GET["p"];
}
if(!file_exists(__DIR__ . '/view/' . $page . '.php')){
    http_response_code(404);
    die('Страница не найдена!');
}
$page = include './view/main.layout.php';
die($page);