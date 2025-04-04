<?php

\session_start();

// load config file
require_once './../init/load.php';

require_once './inc/vars.php';

require_once './inc/funs.php';

require_once './inc/auth.php';

if (isset($_GET['val']))
{
    $_SESSION['editing_profile'] = $_GET['val'];
}

\locate('settings.php');
