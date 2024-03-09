<?php

require('../../../config/db.php');
require('../../../lib/crud.php');
require('../../../lib/functions.php');

session_start();

if (!isset($_SESSION['B2M_TOMBOLA_USER_ID'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit();
}

if (!isset($_POST['pwd'])) {
    header('location:../../../../public?page=settings&error=1');
    exit;
} else {
    $user = DB_read('users', ['ID' => $_SESSION['B2M_TOMBOLA_USER_ID']]);

    $conn = connect_DB();
    $pwd = mysqli_real_escape_string($conn, htmlspecialchars($_POST['pwd']));
    mysqli_close($conn);

    if (md5($pwd) !== $user[0]['Password']) {
        header('location:../../../../public?page=settings&error=1');
        exit;
    }


    if ($_POST['first-name'] != '') {
        $conn = connect_DB();
        $firstName = mysqli_real_escape_string($conn, htmlspecialchars($_POST['first-name']));
        mysqli_close($conn);

        DB_update('users', ['FirstName' => $firstName], ['ID' => $_SESSION['B2M_TOMBOLA_USER_ID']]);
    }


    if ($_POST['last-name'] != '') {
        $conn = connect_DB();
        $lastName = mysqli_real_escape_string($conn, htmlspecialchars($_POST['last-name']));
        mysqli_close($conn);

        DB_update('users', ['LastName' => $lastName], ['ID' => $_SESSION['B2M_TOMBOLA_USER_ID']]);
    }

    if ($_POST['email'] != '')
        if (check_email($_POST['email'])) {
            $conn = connect_DB();
            $email = mysqli_real_escape_string($conn, htmlspecialchars($_POST['email']));
            mysqli_close($conn);

            DB_update('users', ['Email' => $email], ['ID' => $_SESSION['B2M_TOMBOLA_USER_ID']]);
        } else {
            header('location:../../../../public?page=settings&error=2');
            exit;
        }

    if ($_POST['new-pwd'] != '' || $_POST['new-pwd2'] != '')
        if ($_POST['new-pwd'] == '' || $_POST['new-pwd2'] == '') {
            header('location:../../../../public?page=settings&error=3');
            exit;
        } else {
            $conn = connect_DB();
            $newPwd = mysqli_real_escape_string($conn, htmlspecialchars($_POST['new-pwd']));
            $newPwd2 = mysqli_real_escape_string($conn, htmlspecialchars($_POST['new-pwd2']));
            mysqli_close($conn);

            if ($newPwd !== $newPwd2) {
                header('location:../../../../public?page=settings&error=4');
                exit;
            } else
                DB_update('users', ['Password' => md5($newPwd)], ['ID' => $_SESSION['B2M_TOMBOLA_USER_ID']]);
        }

    DB_update('users', ['Participate' => isset($_POST['participate']) ? 1 : 0], ['ID' => $_SESSION['B2M_TOMBOLA_USER_ID']]);
}

header('Location:../../../../public?page=settings');
