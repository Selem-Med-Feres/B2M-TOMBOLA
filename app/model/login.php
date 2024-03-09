<?php

if (isset($_POST['email']) && isset($_POST['pwd'])) {
    $conn = connect_DB();

    $email = mysqli_real_escape_string($conn, htmlspecialchars($_POST['email']));
    $password = mysqli_real_escape_string($conn, htmlspecialchars($_POST['pwd']));

    mysqli_close($conn);

    if ($email == "" || $password == "") {
        header('location:?error=1');
        exit();
    }

    if (!check_email($email)) {
        header('location:?error=2');
        exit();
    }

    $user = DB_read('users', ['Email' => $email, 'Password' => md5($password)], ['Admin', 'ID']);
    $remember_me = $_POST['remember-me'];

    if (!empty($user)) {
        if ($remember_me) {
            setcookie('B2M_MT_REMEMBER_ME', true, time() + (315600 * 1000));
            setcookie('B2M_MT_USER_ID', $user[0]['ID'], time() + (315600 * 1000));
        }

        $_SESSION['B2M_TOMBOLA_LOGGED_IN'] = true;
        $_SESSION['B2M_TOMBOLA_USER_ID'] = $user[0]['ID'];
        $_SESSION['B2M_TOMBOLA_ADMIN'] = boolval($user[0]['Admin']);
        $_SESSION['B2M_TOMBOLA_ENC_KEY'] = bin2hex(random_bytes(32));
        $_SESSION['B2M_TOMBOLA_ENC_IV'] = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));

        header('location:?');
        exit();
    } else {
        header('location:?error=2');
        exit();
    }
}

unset($_POST['email'], $_POST['pwd']);

$message = [];
$error = isset($_GET['error']) ? intval($_GET['error']) : 0;

switch ($error) {
    case 0:
        $message['class'] = 'light-bg';
        $message['text'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pretium.';
        break;

    case 1:
        $message['class'] = 'orange-bg';
        $message['text'] = 'Votre email et votre mot de passe sont requis pour vous connecter à votre compte. Assurez-vous de remplir les champs et réessayez.';
        break;

    case 2:
        $message['class'] = 'red-bg';
        $message['text'] = "Les informations d'identification sont incorrectes. Veuillez vérifier votre adresse e-mail et votre mot de passe, puis réessayer.";
        break;
}
