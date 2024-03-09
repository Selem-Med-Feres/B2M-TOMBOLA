<?php

require('../../../config/db.php');
require('../../../lib/crud.php');
require('../../../lib/functions.php');

session_start();

if (!isset($_SESSION['B2M_TOMBOLA_USER_ID'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit();
}

if (isset($_POST['draw-id'])) {
    $conn = connect_DB();
    $drawId = mysqli_real_escape_string($conn, htmlspecialchars($_POST['draw-id']));
    mysqli_close($conn);

    if (DB_read('users', ['ID' => decryptData($drawId) - 1000]))
        DB_update('users', ['Draw' => decryptData($drawId) - 1000], ['ID' => $_SESSION['B2M_TOMBOLA_USER_ID']]);

    unset($_POST['draw-id']);
}

header('Location:../../../../public');
