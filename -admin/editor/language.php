<?php
\session_start();

// load config file
require_once './../../init/load.php';

require_once './../inc/vars.php';

require_once './../inc/funs.php';

require_once './../inc/auth.php';

$editing_profile_path = __DIR__ . '/../../profiles/' . $_SESSION['editing_profile'] . '/';

$editing_profile_language = \json_decode(\file_get_contents($editing_profile_path . 'languages/English.json'), true);

$msg = '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="./../assets/semantic.min.css">
</head>

<style>body{max-width:1200px;margin: 0 auto;text-align:center}</style>
<body>

<script>
    var PANEL_URL = '<?php echo PANEL_URL; ?>';
</script>

<br><br><br>
<div class="ui centered grid container">
    <div class="fourteen wide column">
        <div class="ui fluid card">
            <div class="content">
                <div class="ui secondary  menu">
                    <div class="item">
                        <div class="ui big breadcrumb">
                            <a class="section" href="<?php echo PANEL_URL; ?>/home.php">Profile Settings</a>
                            <div class="divider"> / </div>
                            <a class="section" href="<?php echo PANEL_URL; ?>/settings.php"><?php echo $_SESSION['editing_profile']; ?></a>
                            <div class="divider"> / </div>
                            <div class="active section" >Language Strings</div>
                        </div>
                    </div>
                    <div class="right menu">
                        <div class="item">
                            <a class="ui button tiny negative" href="<?php echo PANEL_URL; ?>/logout.php"> Logout</a>
                        </div>
                    </div>
                </div>

                <div class="center" style="width:100%;">

                <br>

                <form class="ui form" method="post">

                    <?php

                    if (isset($_POST) && ! empty($_POST))
                    {
                        $error = false;

                        foreach ($_POST as $key => $value)
                        {
                            $editing_profile_language[$key] = $value;
                        }

                        if ($_SESSION['editing_profile'] == 'System-Default')
                        {
                            $error = '<div class="ui ignored negative message"><b>System-Default</b> profile is not allowed to be configured[for security/maintenance reasons]. Please create a copy of System-Default profile and then use that profile for modifications.</div>';
                        }

                        if ( ! $error)
                        {
                            \file_put_contents($editing_profile_path . 'languages/English.json', \json_encode($editing_profile_language, JSON_PRETTY_PRINT));
                        }

                        if ($error)
                        {
                            $msg = $error;
                        }
                        else {
                            $msg = '<div class="ui ignored positive message">Settings Saved</div>';
                        }
                    }

                    if ($msg)
                    {
                        echo $msg;
                    }

                    foreach ($editing_profile_language as $key => $value)
                    {
                        echo '<div class="field">
                            <label>' . $key . '</label>
                                <input type="text" name="' . $key . '" placeholder="' . $key . '" value="' . $value . '">
                            </div>';
                    }

                    ?>

                    <button class="ui button green" type="submit">Save changes</button>

                </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="./../assets/jquery.js"></script>
<script src="./../assets/semantic.min.js"></script>
<script src="./../assets/main.js"></script>
</body>
</html>