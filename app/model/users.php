<?php

$currUser = DB_read('users', ['ID' => $_SESSION['B2M_TOMBOLA_USER_ID']]);
$users = DB_read('users', []);

if ($currUser[0]['Admin'] != 1) {
    header('Location:?page=dashboard');
    exit;
}

function user_draw($userId)
{
    if ($draw = DB_read('users', ['ID' => $userId])) return ($draw[0]['FirstName'] . ' ' . $draw[0]['LastName']);
}
