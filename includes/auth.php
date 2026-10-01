<?php
require_once 'config.php';

class Auth
{
    public static function checkLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login.php');
            exit();
        }
    }

    public static function checkRole($allowedRoles)
    {
        self::checkLogin();
        if (!in_array($_SESSION['user_role'], $allowedRoles)) {
            header('Location: ' . BASE_URL . '/index.php');
            exit();
        }
    }

    public static function login($user)
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['username'] = $user['username'];
    }

    public static function logout()
    {
        session_destroy();
        header('Location: ' . BASE_URL . '/login.php');
        exit();
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}
