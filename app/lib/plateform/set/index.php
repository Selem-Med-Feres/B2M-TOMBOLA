<?php

require('../../../config/db.php');
require('../../../lib/crud.php');
require('../../../lib/functions.php');

session_start();

if (!isset($_SESSION['B2M_TOMBOLA_USER_ID'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit();
}

if (empty($_POST['draw-date']) || empty($_POST['draw-time']) || empty($_POST['end-date']) || empty($_POST['end-time']) || empty($_POST['budget'])) {
    header('Location:../../../../public?page=settings&settings=plateform&error=5');
    exit;
}

$conn = connect_DB();
$DrawDate = mysqli_real_escape_string($conn, htmlspecialchars($_POST['draw-date']));
$DrawTime = mysqli_real_escape_string($conn, htmlspecialchars($_POST['draw-time']));
$EndDate = mysqli_real_escape_string($conn, htmlspecialchars($_POST['end-date']));
$EndTime = mysqli_real_escape_string($conn, htmlspecialchars($_POST['end-time']));
$Budget = mysqli_real_escape_string($conn, htmlspecialchars($_POST['budget']));
mysqli_close($conn);

$DrawDateTime = DateTime::createFromFormat('Y-m-d H:i', $DrawDate . ' ' . substr($DrawTime, 0, 5));
$EndDateTime = DateTime::createFromFormat('Y-m-d H:i', $EndDate . ' ' . substr($EndTime, 0, 5));

if ($DrawDateTime > $EndDateTime) {
    header('Location:../../../../public?page=settings&settings=plateform&error=6');
    exit;
}

if ($Budget < 0) {
    header('Location:../../../../public?page=settings&settings=plateform&error=7');
    exit;
}

DB_update('settings', ['DrawDate' => $DrawDate, 'DrawTime' => $DrawTime, 'EndDate' => $EndDate, 'EndTime' => $EndTime, 'Fee' => $Budget], ['PK' => '']);


header('Location:../../../../public?page=settings&settings=plateform');
