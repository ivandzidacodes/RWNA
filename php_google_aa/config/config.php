<?php

// session_start();

require_once '../vendor/autoload.php';

$google_client = new Google_Client();

$google_client->setClientId('512994314316-dhcq63d41qvm6mna34i1lni0g7tjkd4b.apps.googleusercontent.com');

$google_client->setClientSecret('R-cVbWBGjy_sylm4zDxZuewt');

$google_client->setRedirectUri('http://localhost/php_google_aa/api/index.php');

$google_client->addScope('email');

$google_client->addScope('profile');

?>