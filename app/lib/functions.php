<?php

function page()
{
    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
    if (!user() && !($page == 'register' || $page == 'forgot-password')) return ('login');
    if (user() && ($page == 'register' || $page == 'forgot-password' | $page == 'login')) return ('dashboard');

    return ($page);
}

function user()
{
    return isset($_SESSION['B2M_TOMBOLA_LOGGED_IN']) ? $_SESSION['B2M_TOMBOLA_LOGGED_IN'] : false;
}

function check_email($email)
{
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
    else return false;
}

function encryptData($data)
{
    $encryptedData = openssl_encrypt($data, 'AES-256-CBC', $_SESSION['B2M_TOMBOLA_ENC_KEY'], OPENSSL_RAW_DATA, $_SESSION['B2M_TOMBOLA_ENC_IV']);
    $ciphertext = $_SESSION['B2M_TOMBOLA_ENC_IV'] . $encryptedData;
    return base64_encode($ciphertext);
}

function decryptData($ciphertext)
{
    $ciphertext = base64_decode($ciphertext);
    $ivSize = openssl_cipher_iv_length('AES-256-CBC');
    $iv = substr($ciphertext, 0, $ivSize);
    $encryptedData = substr($ciphertext, $ivSize);
    $decryptedData = openssl_decrypt($encryptedData, 'AES-256-CBC', $_SESSION['B2M_TOMBOLA_ENC_KEY'], OPENSSL_RAW_DATA, $iv);
    return $decryptedData;
}
