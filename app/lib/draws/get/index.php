<?php

require('../../../config/db.php');
require('../../../lib/crud.php');
require('../../../lib/functions.php');

session_start();
if (!isset($_SESSION['B2M_TOMBOLA_USER_ID'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit();
}

$draws = array();
$selected = isset($_GET['selected']) ? $_GET['selected'] : '';

$conn = connect_DB();
$selected = mysqli_real_escape_string($conn, htmlspecialchars($selected));

foreach (DB_read('users', ['Participate' => 1]) as $draw) {
    $status = '';

    foreach (DB_read('users', ['Participate' => 1]) as $user)
        if ($draw['ID'] == $user['Draw']) $status = 'reserved';

    if (encryptData($draw['ID'] + 1000) === $selected) $status .= ' selected';
    if ($draw['ID'] !== $_SESSION['B2M_TOMBOLA_USER_ID']) $draws[encryptData($draw['ID'] + 1000)] = $status;
}

header('Content-Type: application/json');
echo json_encode($draws);
