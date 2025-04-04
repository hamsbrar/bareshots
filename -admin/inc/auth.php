<?php

$password = $_SESSION['password'] ?? false;

if ( ! $password || $password != SYSTEM_PASSWORD)
{
    \locate('login.php');
}
