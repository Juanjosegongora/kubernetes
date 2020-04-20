<?php

define('DB_HOST', 'mysql:3306');
define('DB_NAME', 'proyecto');
define('DB_USER', 'lamp_user');
define('DB_PASSWORD', 'lamp_password');

$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

?>