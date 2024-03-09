<?php

defined('APP_PATH') || define('APP_PATH', realpath(dirname(__FILE__) . '/../app'));
const DS = DIRECTORY_SEPARATOR;

require APP_PATH . DS . 'config' . DS . 'root.php';
require APP_PATH . DS . 'config' . DS . 'db.php';
require APP_PATH . DS . 'config' . DS . 'config.php';

session_start();
session_regenerate_id();

date_default_timezone_set('Africa/Tunis');

$logout = isset($_GET['logout']) ? $_GET['logout'] : false;
if ($logout == 'true') {
    setcookie('B2M_MT_REMEMBER_ME', false, time() - (315600 * 1000));
    setcookie('B2M_MT_USER_ID', "", time() - (315600 * 1000));

    session_unset();
    header("location:?");
    exit;
}

$remember_me = isset($_COOKIE['B2M_MT_REMEMBER_ME']) ? $_COOKIE['B2M_MT_REMEMBER_ME'] : false;
if ((!user()) && $remember_me) {
    $_SESSION['B2M_TOMBOLA_LOGGED_IN'] = true;
    $_SESSION['B2M_TOMBOLA_USER_ID'] = $_COOKIE['B2M_MT_USER_ID'];
}

$page = page();
$model = $root['MODEL_PATH'] . $page . '.php';
$view = $root['VIEW_PATH'] . $page . '.phtml';
$main_content = $root['VIEW_PATH'] . '404.phtml';

if (file_exists($model)) require $model;
if (file_exists($view)) $main_content = $view;

include $root['VIEW_PATH'] . 'layout.phtml';
