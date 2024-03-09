<?php


if (isset($_POST['email']) && isset($_POST['pwd']) && isset($_POST['pwd2']) && isset($_POST['first-name']) && isset($_POST['last-name'])) {
    $conn = connect_DB();
    $email = mysqli_real_escape_string($conn, htmlspecialchars($_POST['email']));
    $password = mysqli_real_escape_string($conn, htmlspecialchars($_POST['pwd']));
    $password2 = mysqli_real_escape_string($conn, htmlspecialchars($_POST['pwd2']));
    $firstName = mysqli_real_escape_string($conn, htmlspecialchars($_POST['first-name']));
    $lastName = mysqli_real_escape_string($conn, htmlspecialchars($_POST['last-name']));
    mysqli_close($conn);

    if ($email == "" || $password == "" || $password2 == "" || $firstName == "" || $lastName == "") {
        header('location:?page=register&error=1');
        exit();
    }

    if ($password !== $password2) {
        header('location:?page=register&error=2');
        exit();
    }

    if (!check_email($email)) {
        header('location:?page=register&error=3');
        exit();
    }

    $user = DB_read('users', ['Email' => $email], ['ID']);

    if (!empty($user)) {
        header('location:?page=register&error=4');
        exit();
    } else {
        if (DB_create('users', ['FirstName', 'LastName', 'Email', 'Password'], [$firstName, $lastName, $email, md5($password)])) {
            $user = DB_read('users', ['Email' => $email], ['ID']);

            $_SESSION['B2M_TOMBOLA_LOGGED_IN'] = true;
            $_SESSION['B2M_TOMBOLA_USER_ID'] = $user[0]['ID'];
            $_SESSION['B2M_TOMBOLA_ADMIN'] = false;
            $_SESSION['B2M_TOMBOLA_ENC_KEY'] = bin2hex(random_bytes(32));
            $_SESSION['B2M_TOMBOLA_ENC_IV'] = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));

            header('location:?');
            exit();
        } else {
            header('location:?page=register&error=5');
            exit();
        }
    }
}

unset($_POST['email'], $_POST['pwd'], $_POST['pwd2'], $_POST['first-name'], $_POST['last-name']);

$message = [];
$error = isset($_GET['error']) ? intval($_GET['error']) : 0;

switch ($error) {
    case 0:
        $message['class'] = 'light-bg';
        $message['text'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pretium.';
        break;

    case 1:
        $message['class'] = 'orange-bg';
        $message['text'] = 'Toutes les informations sont requises sont requises pour créer compte. Assurez-vous de remplir tous les champs et réessayez.';
        break;

    case 2:
        $message['class'] = 'red-bg';
        $message['text'] = "Mots de passe ne correspondent pas. Veuillez vérifier votre mot de passe puis réessayer.";
        break;

    case 3:
        $message['class'] = 'red-bg';
        $message['text'] = "E-mail incorrect. Veuillez vérifier votre adresse e-mail puis réessayer.";
        break;

    case 4:
        $message['class'] = 'orange-bg';
        $message['text'] = "Cet adresse e-mail est associée à un compte existant sur B2M Tombola.";
        break;

    case 5:
        $message['class'] = 'red-bg';
        $message['text'] = "Une erreur s'est produite. Veuillez réessayer ultirièrement ou contacter l'équipe support.";
        break;
}
