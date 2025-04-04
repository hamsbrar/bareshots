<?php
\session_start();

// load config file
require_once './../init/load.php';

require_once './inc/vars.php';

require_once './inc/funs.php';

require_once './inc/auth.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="./assets/semantic.min.css">
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
                            <div class="active section" >Profile Settings</div>
                        </div>
                    </div>

                    <div class="right menu">
                        <div class="item">
                            <a class="ui button tiny negative" href="<?php echo PANEL_URL; ?>/logout.php"> Logout</a>
                        </div>
                    </div>

                </div>

                <div class="ui items">

                <?php

                $dirs = \array_filter(\glob('./../profiles/*'), 'is_dir');

                foreach ($dirs as $dir)
                {
                    $profile_name = \basename($dir);

                    if ($MASTER['global_json']['active_profile'] == $profile_name)
                    {
                        $btns = '<button class="ui positive tiny button"><i class="info circle icon"></i> Active</button>';
                    }
                    else {
                        $btns = '<a href="' . PANEL_URL . '/actions.php?type=set_active_profile&val=' . $profile_name . '" class="ui tiny button"><i class="check icon"></i> Set active</a>';
                    }

                    $menu_items = '';

                    if ($profile_name != 'System-Default')
                    {
                        $menu_items .= '<div class="item" onclick="renameProfile(\'' . $profile_name . '\')"><i class="i cursor icon"></i> Rename</div>';

                        $menu_items .= '<a class="item" href="' . PANEL_URL . '/actions.php?type=delete_profile&val=' . $profile_name . '" class="ui tiny negative button"><i class="trash icon"></i> Delete</a>';
                    }

                    $menu_items .= '<a class="item" href="' . PANEL_URL . '/actions.php?type=create_profile_copy&val=' . $profile_name . '"><i class="copy icon"></i> Create Copy</a>';

                    $btns .= '<a class="ui tiny button" href="' . PANEL_URL . '/edit_profile.php?val=' . $profile_name . '"><i class="cog icon"></i> Edit</a>';

                    $btns .= '<div class="ui icon tiny top right pointing dropdown basic button">
                                <i class="chevron down icon"></i>
                                <div class="menu">
                                ' . $menu_items . '
                                </div>
                            </div>';

                    echo '
                    <div class="item" id="profile-' . $profile_name . '">
                        <div class="image">
                            <img src="' . URL . '/profiles/' . $profile_name . '/brand/logo.jpg">
                        </div>
                        <div class="content">
                        <a class="header"  href="' . PANEL_URL . '/edit_profile.php?val=' . $profile_name . '" >' . $profile_name . '</a>
                        <div class="description">
                            ' . $btns . '
                        </div>
                        </div>
                    </div>';
                }

                ?>

                </div>

            </div>
        </div>
    </div>
</div>
<script src="./assets/jquery.js"></script>
<script src="./assets/semantic.min.js"></script>
<script src="./assets/main.js"></script>
</body>
</html>