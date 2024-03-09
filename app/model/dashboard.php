<?php

$mode = '';
$currDateTime = new DateTime();
$fee = DB_read('settings', ['PK' => ''], ['Fee']);
$user = DB_read('users', ['ID' => $_SESSION['B2M_TOMBOLA_USER_ID']]);
$giftTo = DB_read('users', ['ID' => $user[0]['Draw']], ['FirstName', 'LastName']);

$DrawDate = DB_read('settings', ['PK' => ''], ['DrawDate']);
$DrawTime = DB_read('settings', ['PK' => ''], ['DrawTime']);
$DrawDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $DrawDate[0]['DrawDate'] . ' ' . $DrawTime[0]['DrawTime']);

$EndDate = DB_read('settings', ['PK' => ''], ['EndDate']);
$EndTime = DB_read('settings', ['PK' => ''], ['EndTime']);
$EndDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $EndDate[0]['EndDate'] . ' ' . $EndTime[0]['EndTime']);

if ($currDateTime < DateTime::createFromFormat('Y-m-d H:i:s', $DrawDateTime->format('Y-m-d H:i:s'))) $mode = 'Register';
else if ($user[0]['Draw'] == null) $mode = 'Draw';
else $mode = 'Gift';