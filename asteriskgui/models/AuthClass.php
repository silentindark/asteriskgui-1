<?php

namespace app\models;

class AuthClass
{
    private $_login;
    private $_password;

    function __construct()
    {
        $config = include(__DIR__ . "/../db/config.php");
        $this->_login = $config['User']['login']; //Устанавливаем логин
        $this->_password = $config['User']['password']; //Устанавливаем пароль
    }

    public function isAuth()
    {
        if (isset($_SESSION["is_auth"])) { //Если сессия существует
            return $_SESSION["is_auth"]; //Возвращаем значение переменной сессии is_auth (хранит true если авторизован, false если не авторизован)
        } else return false; //Пользователь не авторизован, т.к. переменная is_auth не создана
    }

    public function auth($login, $passwords)
    {
        if ($login == $this->_login && $passwords == $this->_password) { //Если логин и пароль введены правильно
            $_SESSION["is_auth"] = true; //Делаем пользователя авторизованным
            $_SESSION["login"] = $login; //Записываем в сессию логин пользователя
            return true;
        } else { //Логин и пароль не подошел
            $_SESSION["is_auth"] = false;
            return false;
        }
    }

    public function getLogin()
    {
        if ($this->isAuth()) { //Если пользователь авторизован
            return $_SESSION["login"]; //Возвращаем логин, который записан в сессию
        }
        return null;
    }

    public function logout()
    {
        $_SESSION = array(); //Очищаем сессию
        session_destroy(); //Уничтожаем
    }
}