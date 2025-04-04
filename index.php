<?php

\session_start();

require_once 'debugger.php';

require_once 'init/load.php';

use Inc\System\Router;
use Inc\System\Responder;

Router::route();

if ('-admin' == ($ROUTES['CURRENT_ROUTE_SELECTED_VALUES']['domain_followed'] ?? ''))
{
    \header('location: ' . URL . '/-admin/home.php');
}
else {
    Responder::respond($ROUTES['CURRENT_ROUTE_SELECTED']);
}
