<?php

\session_start();

// load config file
require_once './../init/load.php';

require_once './inc/vars.php';

require_once './inc/funs.php';

require_once './inc/auth.php';

if ($_SESSION['editing_profile'] == 'System-Default')
{
    \locate('settings.php');
}
elseif (isset($_GET['path']))
{
    $_SESSION['path'] = $_GET['path'];

    $_SESSION['url'] = URL;

    $_SESSION['root_path'] = PATH . '/profiles/' . $_SESSION['editing_profile'] . '/' . $_SESSION['path'] . (isset($_GET['no_dot_files']) ? '' : '/.files/');

    $_SESSION['panel_url'] = PANEL_URL;

    $_SESSION['back_title'] = $_GET['back_title'];

    $_SESSION['back_path'] = \rawurldecode($_GET['back_path']);

    $full_path = __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $_GET['path'];

    if (\file_exists($full_path . '/.files/'))
    {
        $_SESSION['editing_path'] = $full_path . '/.files/';
    }
    else {
        $_SESSION['editing_path'] = $full_path;
    }

    \locate('files/manager.php');
}
