<?php

$code = "dasilva";
$code = "mista";
$password   = password_hash($code, PASSWORD_BCRYPT);

echo $password;
