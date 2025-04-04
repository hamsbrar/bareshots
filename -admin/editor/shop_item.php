<?php
\session_start();

// load config file
require_once './../../init/load.php';

require_once './../inc/vars.php';

require_once './../inc/funs.php';

require_once './../inc/auth.php';

$editing_profile_path = __DIR__ . '/../../profiles/' . $_SESSION['editing_profile'] . '/';

$path = \ltrim(\rtrim($_GET['path'], '/'), '/');

$item = \ltrim(\rtrim($_GET['item'], '/'), '/');

if ( ! \file_exists($editing_profile_path . $path . '/.files/' . $item))
{
    \locate('menu.php?path=' . $path);

    exit();
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

                            <div class="divider"> / </div>

                            <a class="section" href="<?php echo PANEL_URL; ?>/editor/items_lister.php?path=<?php echo $path; ?>&type=shop&title=Shop Items">Shop Items</a>

                            <div class="divider"> / </div>

                            <div class="section" ><?php echo $item; ?></div>
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

                <?php

                    $carousel_files_dir = __DIR__ . '/../../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/';

                    $shop_item_json = $carousel_files_dir . '/.files/' . $item . '.json';

                    if (\file_exists($shop_item_json))
                    {
                        $shop_item_json_config = \json_decode(\file_get_contents($shop_item_json), true);
                    }

                    if ( ! isset($shop_item_json_config['properties']))
                    {
                        $shop_item_json_config['properties'] = array(
                            'title'       => '',
                            'price'       => '',
                            'description' => '',
                        );
                    }

                    if (isset($_POST) && ! empty($_POST))
                    {
                        $shop_item_json_config['properties']['title'] = $_POST['title'];
                        $shop_item_json_config['properties']['price'] = $_POST['price'];
                        $shop_item_json_config['properties']['description'] = $_POST['description'];

                        if ($_SESSION['editing_profile'] == 'System-Default')
                        {
                            $msg = '<div class="ui ignored negative message"><b>System-Default</b> profile is not allowed to be configured[for security/maintenance reasons]. Please create a copy of System-Default profile and then use that profile for modifications.</div>';
                        }
                        else {
                            \file_put_contents($shop_item_json, \json_encode($shop_item_json_config, JSON_PRETTY_PRINT));

                            $msg = '<div class="ui ignored positive message">Settings Saved</div>';
                        }
                    }
                ?>

                <form class="ui form" method="post">

                    <?php

                    echo $msg;

                    foreach ($shop_item_json_config['properties'] as $key => $value)
                    {
                        switch ($MASTER['global_json']['system_config']['shop_config'][$key]['config']['input_type'])
                        {
                            case 'dropdown':
                                $possible_values = \getPossibleValues($MASTER['global_json']['system_config']['shop_config'][$key]['config']);

                                $input = '<select name="' . $key . '" class="ui dropdown">
                                ';

                                foreach ($possible_values as $p_value => $p_value_title)
                                {
                                    $input .= '<option ' . ($value == $p_value ? 'selected' : '') . ' value="' . $p_value . '">' . $p_value_title . '</option>';
                                }

                                $input .= '</select>';

                            break;

                            default:
                                $input = '<input type="text" name="' . $key . '" placeholder="' . $key . '" value="' . $value . '">';

                            break;
                        }

                        echo '<div class="field">
                                <h5 class="ui header">
                                    <label>' . $MASTER['global_json']['system_config']['shop_config'][$key]['config']['name'] . '</label>
                                    <div class="sub header">
                                        ' . $MASTER['global_json']['system_config']['shop_config'][$key]['config']['description'] . '
                                    </div>
                                </h5>
                                ' . $input . '
                            </div><br>';
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