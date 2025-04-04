<?php
\session_start();

require_once './../init/load.php';

require_once './inc/vars.php';

require_once './inc/funs.php';

if (isset($_POST['pass']))
{
    if ($_POST['pass'] == SYSTEM_PASSWORD)
    {
        $_SESSION['password'] = $_POST['pass'];

        \locate('home.php');
    }
}
                
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Login</title>
    <link rel="stylesheet" href="./assets/semantic.min.css">
</head>
<style>body{max-width:1200px;margin: 0 auto;text-align:center}</style>
<body>
<br><br><br>
<div class="ui centered grid container">
    <div class="fourteen wide column">

        <div class="ui fluid card">

            <div class="content">
                <?php
                if (isset($_POST['pass']))
                {
                    if ($_POST['pass'] != SYSTEM_PASSWORD)
                    {
                        echo '<div class="ui icon warning message"><div class="content"><div class="header">Login failed!</div><p>You might have misspelled your password!</p></div></div>';
                    }
                }
                ?>
                <form class="ui form" method="POST">
                    <div class="field">
                        <label>Password</label>
                        <input type="password" name="pass" placeholder="Password">
                    </div>
                    <button class="ui primary button" type="submit">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="./assets/semantic.min.js"></script>
</body>
</html>