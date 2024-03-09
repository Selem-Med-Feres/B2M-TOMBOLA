<?php

$settings = DB_read('settings', ['PK' => '']);
$user = DB_read('users', ['ID' => $_SESSION['B2M_TOMBOLA_USER_ID']]);
$Options = isset($_GET['settings']) ? ($_GET['settings'] == 'plateform' ? 'plateform-settings' : 'account-settings') : 'account-settings';
$Options = $user[0]['Admin'] ? $Options : 'account-settings';

$message = '';
$error = isset($_GET['error']) ? intval($_GET['error']) : 0;

switch ($error) {
    case 1:
        $message = 'Mot de passe actuel incorrect. Vous devez saisir votre mot de passe actuel correctement afin de pouvoir mettre à jour votre profil.';
        break;

    case 2:
        $message = "E-mail incorrect. Vous devez introduire une nouvelle adresse e-mail correcte pour mettre à jour votre adresse actuelle.";
        break;

    case 3:
        $message = "Le nouveau mot de passe et la confirmation su nouveau mot de passe sont requis pour mettre à jour votre mot de passe.";
        break;

    case 4:
        $message = "Mots de passe ne correspondent pas. Veuillez vérifier votre nouveau mot de passe puis réessayer.";
        break;

    case 5:
        $message = "Tous les paramètres de B2M-TOMBOLA sont requis.";
        break;

    case 6:
        $message = "La date de fin ne peut pas être antérieure à la date du tirage.";
        break;

    case 7:
        $message = "Le budget du cadeau ne peut pas être négatif.";
        break;
}
