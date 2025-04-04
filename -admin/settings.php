<?php
\session_start();

// load config file
require_once './../init/load.php';

require_once './inc/vars.php';

require_once './inc/funs.php';

require_once './inc/auth.php';

$editing_profile_path = __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/';

$editing_profile_menu = \json_decode(\file_get_contents($editing_profile_path . 'menu/menu.json'), true);

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
                            <a class="section" href="<?php echo PANEL_URL; ?>/home.php">Profile Settings</a>
                            <div class="divider"> / </div>
                            <div class="active section"><?php echo $_SESSION['editing_profile']; ?></div>
                        </div>
                    </div>

                    <div class="right menu">
                        <div class="item">
                            <a class="ui button tiny negative" href="<?php echo PANEL_URL; ?>/logout.php"> Logout</a>
                        </div>
                    </div>

                </div>

                <div class="ui grid container">

                    <div class="four wide column">

                        <div class="ui vertical menu center">
                            <a href="<?php echo PANEL_URL; ?>/editor/config.php?key=website&title=Website Settings" class="item">Website Settings</a>
                            <a href="<?php echo PANEL_URL; ?>/editor/config.php?key=seo&title=SEO Settings" class="item">SEO Settings</a>
                            <a href="<?php echo PANEL_URL; ?>/editor/config.php?key=theme&title=Theme Settings" class="item">Theme Settings</a>
                            <a href="<?php echo PANEL_URL; ?>/editor/config.php?key=footer&title=Footer Settings" class="item">Footer Settings</a>
                            <a href="<?php echo PANEL_URL; ?>/editor/config.php?key=contact&title=Contact Settings" class="item">Contact Settings</a>
                            <a href="<?php echo PANEL_URL; ?>/editor/config.php?key=social&title=Social Settings" class="item">Social Settings</a>
                            <a href="<?php echo PANEL_URL; ?>/editor/config.php?key=icons&title=Social Icons Settings" class="item">Social Icons Settings</a>
                            <a href="<?php echo PANEL_URL; ?>/editor/config.php?key=mailer&title=Mailer Settings" class="item">Mailer Settings</a>
                            <a href="<?php echo PANEL_URL; ?>/editor/config.php?key=assets&title=Assets Settings" class="item">Assets Settings</a>
                            <a href="<?php echo PANEL_URL; ?>/editor/language.php" class="item">Language Strings</a>
                        </div>

                    </div>

                    <div class="twelve wide column">

                        <br>

                        <h2 class="ui header">
                            <div class="content">
                                Interactive Menu Editor
                                <div class="sub header">
                                    Check out Dynamic, Interactive way of editing/configuring your website menu
                                </div>
                                    <h3><a href="<?php echo PANEL_URL; ?>/editor/menu.php?path=menu" ><i class="chevron right icon"></i> Take me to Menu Editor</a></h3>
                            </div>
                        </h2>

                        <br>

                        <h3 class="ui header">
                            <div class="content">
                                Do some Branding!
                                <div class="sub header">
                                    Edit/Upload logo, favicons and much more to be used directly in the website
                                </div>
                                    <h5>
                                        <a href="<?php echo PANEL_URL; ?>/fm.php?path=brand&no_dot_files=true&back_title=Back to Profile&back_path=/settings.php"><i class="chevron right icon"></i> Open File Manager</a>
                                    </h5>
                            </div>
                        </h3>

                    </div>

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