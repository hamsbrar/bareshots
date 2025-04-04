<?php
\session_start();

// load config file
require_once './../../init/load.php';

require_once './../inc/vars.php';

require_once './../inc/funs.php';

require_once './../inc/auth.php';

$editing_profile_path = __DIR__ . '/../../profiles/' . $_SESSION['editing_profile'] . '/';

$path = \ltrim(\rtrim($_GET['path'], '/'), '/');

$title = $_GET['title'];

$type = $_GET['type'];

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

                            <div class="section" ><?php echo $title; ?></div>
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
                            <tr>
                                <th>Item Name</th>
                                <th>Ops</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php

                        $items_files_dir = __DIR__ . '/../../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/';

                        $images = \glob($items_files_dir . '.files/*.{jpg,png}', GLOB_BRACE);

                        foreach ($images as $image)
                        {
                            $basename = \basename($image);

                            echo '
                            <tr>
                                <td>
                                    <img src="' . URL . '/profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/.files/' . $basename . '" class="ui tiny rounded image">
                                    ' . $basename . '
                                </td>
                                <td><a class="ui button tiny" href="' . PANEL_URL . '/editor/' . $type . '_item.php?path=' . $path . '&item=' . $basename . '"><i class="cog icon"></i> Edit Item</a></td>
                            </tr>
                            ';
                        }

                        ?>

                        <?php if ($_SESSION['editing_profile'] != 'System-Default')
                        {
                            ?>
                            <tr>
                                <td> - </td>
                                <td><a class="ui button tiny" href="<?php echo PANEL_URL; ?>/fm.php?path=<?php echo $path; ?>&back_title=Back to <?php echo $title; ?>&back_path=<?php echo \rawurlencode('/editor/items_lister.php?path=' . $path . '&type=' . $type . '&title=' . $title); ?>"><i class="plus icon"></i> Add/Remove Items</a></td>
                            </tr>
                        <?php
                        }
                        ?>

                        </tbody>

                    </table>

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