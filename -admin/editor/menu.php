<?php
\session_start();

// load config file
require_once './../../init/load.php';

require_once './../inc/vars.php';

require_once './../inc/funs.php';

require_once './../inc/auth.php';

$editing_profile_path = __DIR__ . '/../../profiles/' . $_SESSION['editing_profile'] . '/';

// menu/something
$path = \ltrim(\rtrim($_GET['path'], '/'), '/');

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

<style>
    body.dragging, body.dragging * {
        cursor: move !important;
    }
    .dragged {
        position: absolute;
        opacity: 0.5;
        z-index: 2000;
    }
    ol.example li.placeholder {
        position: relative;
    }
    ol.example li.placeholder:before {
        position: absolute;
    }

    ol li{width:100%;cursor:pointer;padding:10px 15px;border: 2px solid gray;background:lightgray;border-radius:3px;margin:5px}
</style>

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

                <table class="ui celled striped table">
                    <thead>
                        <tr><th><?php echo ($path == 'menu') ? 'Page' : 'Sub-Page'; ?></th>
                        <th>Type</th>
                        <th>Tags</th>
                        <th>Ops</th>
                    </tr></thead>
                    <tbody>

                        <?php

                            // something
                            $basename = \basename($path);

                            $menu_file = __DIR__ . '/../../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $basename . '.json';

                            // yes its a menu/submenu
                            if (\file_exists($menu_file))
                            {
                                $menu_config = \json_decode(\file_get_contents($menu_file), true);

                                $default_item = $menu_config['config']['default'];

                                foreach (\explode(',', $menu_config['config']['order']) as $value)
                                {
                                    if ( ! \trim($value))
                                    {
                                        continue;
                                    }

                                    $tags = $ops = '';

                                    if (( ! isset($menu_config['actions']) || $menu_config['actions'][$value] != 'system_page_portfolio') && $_SESSION['editing_profile'] != 'System-Default')
                                    {
                                        $ops .= '<a href="' . PANEL_URL . '/fm.php?path=' . $path . '/' . $value . '&back_title=Back to Menu Editor&back_path=' . \rawurlencode('/editor/menu.php?path=' . $path) . '" class="ui button tiny"><i class="folder icon"></i> Files</a>';
                                    }

                                    if (\file_exists(__DIR__ . '/../../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $value . '/page_settings.json'))
                                    {
                                        $ops .= '<a class="ui button tiny" href="' . PANEL_URL . '/editor/page_config.php?type=page_settings&path=' . $path . '/' . $value . '"><i class="cog icon"></i> Page Settings</a>';
                                    }

                                    if (isset($menu_config['actions']) && $menu_config['actions'][$value] == 'system_page_home')
                                    {
                                        $ops .= '<a class="ui button tiny" href="' . PANEL_URL . '/editor/items_lister.php?path=' . $path . '/' . $value . '&type=carousel&title=Carousel Items"><i class="slideshare icon"></i> Carousel Items</a>';
                                    }

                                    if (isset($menu_config['actions']) && $menu_config['actions'][$value] == 'system_page_shop')
                                    {
                                        $ops .= '<a class="ui button tiny" href="' . PANEL_URL . '/editor/items_lister.php?path=' . $path . '/' . $value . '&type=shop&title=Shop Items"><i class="shopping bag icon"></i> Shop Items</a>';
                                    }

                                    $action_items = '<div class="item" onclick="renameMenuItem(\'' . $path . '\', \'' . $value . '\')"><i class="i cursor icon"></i>Rename Page</div>';

                                    // [disabled as every page type requires different set of settings which are generated while page creation only.]
                                    // if(isset($menu_config['actions'][$value]))
                                    // {
                                    //     $action_items .= '<div class="item" onclick="changeMenuItemType(\''.$path.'\', \''.$value.'\', \''.$menu_config['actions'][$value].'\')"><i class="cog icon"></i> Change Type</div>';
                                    // }

                                    $action_items .= '<a class="item" href="' . PANEL_URL . '/actions.php?type=delete_menu_item&path=' . $path . '&item=' . $value . '"><i class="trash icon"></i>Delete Page</a>';

                                    if ($default_item == $value)
                                    {
                                        $tags .= '<a class="ui green label">Default Page</a>';
                                    }
                                    else {
                                        $action_items .= '<a class="item" href="' . PANEL_URL . '/actions.php?type=set_default_menu_item&val=' . $value . '&path=' . $path . '" class="ui tiny negative button"><i class="check icon"></i> Set Default</a>';
                                    }

                                    if (isset($menu_config['actions']) && $menu_config['actions'][$value] == 'system_page_portfolio')
                                    {
                                        $tags .= '<a class="ui teal label">Sub-Pages</a>';
                                    }

                                    $actions = '
                                        <div class="ui icon tiny top right pointing dropdown basic button">
                                            <i class="chevron down icon"></i>
                                            <div class="menu">
                                                ' . $action_items . '
                                            </div>
                                        </div>';

                                    echo '<tr class="menu-item">
                                            <td>
                                                <h4 class="ui image header">
                                                    <div class="content js-menu-item-header">
                                                        ' . $value . '
                                                    </div>
                                                </h4>
                                            </td>
                                            <td>
                                                <div class="sub header">
                                                    ' . ($menu_config['actions'][$value] ?? '') . '
                                                </div>
                                            </td>
                                            <td>
                                                ' . ($tags ? $tags : '-') . '
                                            </td>
                                            <td>

                                            ' . (isset($menu_config['actions']) && $menu_config['actions'][$value] == 'system_page_portfolio' ? '<a class="ui button tiny" href="' . PANEL_URL . '/editor/menu.php?path=' . $path . '/' . $value . '"><i class="chevron right icon"></i> Sub-Menu</a>' : '') . '

                                            ' . $ops . '

                                            ' . ($_SESSION['editing_profile'] == 'System-Default' ? '' : $actions) . '

                                            </td>
                                        </tr>';
                                }
                            }
                        ?>

                        <tr>
                            <td>
                                <div class="ui button tiny" onclick="changeMenuOrder('<?php echo $path; ?>');"><i class="list ol icon"></i> Order</div>
                            </td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <div class="ui button tiny" onclick="addNewMenuItem('<?php echo $path; ?>');"><i class="plus icon"></i> Add Page</div>
                            </td>
                        </tr>

                    </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="./../assets/jquery.js"></script>
<script src="./../assets/sortable.js"></script>
<script src="./../assets/semantic.min.js"></script>
<script src="./../assets/main.js"></script>
</body>
</html>