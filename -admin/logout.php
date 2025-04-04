<?php

\session_start();

require_once './../init/load.php';

require_once './inc/vars.php';

require_once './inc/funs.php';

$_SESSION['password'] = false;

\header('location: ' . URL);
