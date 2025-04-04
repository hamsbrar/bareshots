<?php
\session_start();

// load config file
require_once './../../init/load.php';

require_once './../inc/vars.php';

require_once './../inc/funs.php';

require_once './../inc/auth.php';

$editing_profile_path = __DIR__ . '/../../profiles/' . $_SESSION['editing_profile'] . '/';

$to_config_file = $editing_profile_path . $_GET['path'] . '/' . $_GET['type'] . '.json';

if ( ! \file_exists($to_config_file))
{
    \locate('editor/menu.php');

    exit();
}

$editing_page_config = \json_decode(\file_get_contents($to_config_file), true);

if ($_GET['type'] == 'page_settings')
{
    $editing_page_config = $editing_page_config['page_settings'];
}

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

                            <?php
                                $nav_splits = \explode('/', \ltrim(\rtrim($_GET['path'], '/'), '/'));

                                $items_counter = 0;
                                $total_splits = \count($nav_splits);

                                $split_path = '';

                                foreach ($nav_splits as $split)
                                {
                                    $items_counter++;

                                    if ( ! \strlen(\trim($split)))
                                    {
                                        continue;
                                    }

                                    if ($items_counter != $total_splits)
                                    {
                                        echo '<div class="divider"> / </div><a href="' . PANEL_URL . '/editor/menu.php?path=' . $split_path . $split . '" class="section" >' . $split . '</a>';
                                    }
                                    else {
                                        echo '<div class="divider"> / </div><div class="active section" >' . $split . '</div>';
                                    }

                                    $split_path = $split_path . '/' . $split . '/';
                                }

                            ?>

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
                            if (isset($MASTER['global_json']['system_config'][$_GET['type']][$key]['config']))
                            {
                                switch ($MASTER['global_json']['system_config'][$_GET['type']][$key]['config']['input_type'])
                                {
                                    case 'dropdown':
                                    $possible_values = \getPossibleValues($MASTER['global_json']['system_config'][$_GET['type']][$key]['config']);

                                    if ( ! \in_array($value, \array_keys($possible_values)))
                                    {
                                        $error = '<div class="ui ignored negative message">The provided value doesnt exists for <b>' . $key . '</b></div>';
                                    }

                                    break;
                                }
                            }

                            $editing_page_config[$key] = $value;
                        }

                        if ($_SESSION['editing_profile'] == 'System-Default')
                        {
                            $error = '<div class="ui ignored negative message"><b>System-Default</b> profile is not allowed to be configured[for security/maintenance reasons]. Please create a copy of System-Default profile and then use that profile for modifications.</div>';
                        }

                        if ( ! $error)
                        {
                            if ($_GET['type'] == 'page_settings')
                            {
                                $preset_page_config = $editing_page_config;

                                $new_page_config = array();

                                $new_page_config['page_settings'] = $preset_page_config;

                                \file_put_contents($to_config_file, \json_encode($new_page_config, JSON_PRETTY_PRINT));

                                $editing_page_config = $preset_page_config;
                            }
                            else {
                                \file_put_contents($to_config_file, \json_encode($editing_page_config, JSON_PRETTY_PRINT));
                            }
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

                    foreach ($editing_page_config as $key => $value)
                    {
                        if (isset($MASTER['global_json']['system_config'][$_GET['type']][$key]['config']))
                        {
                            switch ($MASTER['global_json']['system_config'][$_GET['type']][$key]['config']['input_type'])
                            {
                                case 'dropdown':
                                    $possible_values = \getPossibleValues($MASTER['global_json']['system_config'][$_GET['type']][$key]['config']);

                                    $input = '<select name="' . $key . '" class="ui dropdown">
                                    ';

                                    foreach ($possible_values as $p_value => $p_value_title)
                                    {
                                        $input .= '<option ' . ($value == $p_value ? 'selected' : '') . ' value="' . $p_value . '">' . $p_value_title . '</option>';
                                    }

                                    $input .= '</select>';

                                break;

                                case 'textarea':
                                    $input = '<textarea name="' . $key . '" id="' . $key . '" class="ui textarea fluid" rows="10">' . $value . '</textarea>';

                                break;

                                default:
                                    $input = '<input type="text" name="' . $key . '" placeholder="' . $key . '" value="' . $value . '">';

                                break;
                            }

                            echo '<div class="field">
                                    <h5 class="ui header">
                                        <label>' . $MASTER['global_json']['system_config'][$_GET['type']][$key]['config']['name'] . '</label>
                                        <div class="sub header">
                                            ' . $MASTER['global_json']['system_config'][$_GET['type']][$key]['config']['description'] . '
                                        </div>
                                    </h5>
                                    ' . $input . '
                                </div><br>';
                        }
                        else {
                            echo '<div class="field">
                            <label>' . $key . '</label>
                                <input type="text" name="' . $key . '" placeholder="' . $key . '" value="' . $value . '">
                            </div>';
                        }
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