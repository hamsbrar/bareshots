<?php

\session_start();

// load config file
require_once './../init/load.php';

require_once './inc/vars.php';

require_once './inc/funs.php';

require_once './inc/auth.php';

if (isset($_GET['type']))
{
    if (\in_array($_GET['type'], array(
        'set_default_menu_item',
        'rename_menu_item',
        'create_new_menu_item',
        'delete_menu_item',
        'change_menu_item_type',
        'new_menu_order',
    )) && $_SESSION['editing_profile'] == 'System-Default')
    {
        \locate('home.php');
    }
    else {
        switch ($_GET['type'])
        {
            case 'set_active_profile': \setActiveProfile($_GET['val']);

            break;

            case 'rename_profile': \setNewProfileName($_GET['val'], $_GET['new_val']);

            break;

            case 'delete_profile': \deleteProfile($_GET['val']);

            break;

            case 'create_profile_copy': \createProfileCopy($_GET['val']);

            break;

            case 'set_default_menu_item': \setDefaultMenuItem($_GET['val'], $_GET['path']);

            break;

            case 'rename_menu_item': \renameMenuItem($_GET['path'], $_GET['old_val'], $_GET['new_val']);

            break;

            case 'change_menu_item_type': \changeMenuItemType($_GET['path'], $_GET['item'], $_GET['new_type']);

            break;

            case 'create_new_menu_item': \createNewMenuItem($_GET['path'], $_GET['item']);

            break;

            case 'delete_menu_item': \deleteMenuItem($_GET['path'], $_GET['item']);

            break;

            case 'new_menu_order': \newMenuOrder($_GET['path'], $_GET['new_order']);

            break;
        }
    }
}

function newMenuOrder($path, $new_order)
{
    $path = \ltrim(\rtrim($_GET['path'], '/'), '/');

    $basename = \basename($path);

    $menu_file = __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $basename . '.json';

    if (\file_exists($menu_file))
    {
        $menu_config = \json_decode(\file_get_contents($menu_file), true);

        $menu_config['config']['order'] = $new_order;

        \file_put_contents($menu_file, \json_encode($menu_config, JSON_PRETTY_PRINT));
    }

    \locate('editor/menu.php?path=' . $path);
}

function deleteMenuItem($path, $item)
{
    $path = \ltrim(\rtrim($_GET['path'], '/'), '/');

    $basename = \basename($path);

    $menu_file = __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $basename . '.json';

    $item = \preg_replace('/[^A-Za-z0-9-]/', '', \trim($item));

    if (\file_exists($menu_file))
    {
        $menu_config = \json_decode(\file_get_contents($menu_file), true);

        $order = \array_values(\array_filter(\explode(',', $menu_config['config']['order']), 'strlen'));

        $new_order = \array_diff($order, array($item));

        $menu_config['config']['order'] = \implode(',', $new_order);

        if ($menu_config['config']['default'] == $item)
        {
            $menu_config['config']['default'] = $new_order[0];
        }

        if (isset($menu_config['actions'][$item]))
        {
            unset($menu_config['actions'][$item]);
        }

        \rrmdir(__DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $item);

        \file_put_contents($menu_file, \json_encode($menu_config, JSON_PRETTY_PRINT));
    }

    \locate('editor/menu.php?path=' . $path);
}

function createNewMenuItem($path, $item)
{
    $path = \ltrim(\rtrim($_GET['path'], '/'), '/');

    $basename = \basename($path);

    $menu_file = __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $basename . '.json';

    $item = \preg_replace('/[^A-Za-z0-9-]/', '', \trim($item));

    if (\strlen($item) > 0 && ! \file_exists(__DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $item))
    {
        if (\file_exists($menu_file))
        {
            $menu_config = \json_decode(\file_get_contents($menu_file), true);
        }
        else {
            $menu_config = array('config' => array('order' => '', 'default' => ''));
        }

        if ($path == 'menu')
        {
            if ( ! isset($_GET['new_type']) || ! \in_array($_GET['new_type'], array(
                'system_page_home',
                'system_page_bio',
                'system_page_portfolio',
                'system_page_shop',
                'system_page_contact',
            )))
            {
                // error in actions dont proceed
            }
            else {
                $menu_config['actions'][$item] = $_GET['new_type'];

                $folders = array(
                    'system_page_home'      => 'home',
                    'system_page_bio'       => 'artist',
                    'system_page_portfolio' => 'portfolio',
                    'system_page_shop'      => 'shop',
                    'system_page_contact'   => 'contact',
                );

                // simple get copy of menu item thats requested from System-Default profile
                \full_copy(__DIR__ . '/../profiles/System-Default/menu/' . $folders[$_GET['new_type']], __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/menu/' . $item);

                if (\file_exists(__DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/menu/' . $item . '/' . $folders[$_GET['new_type']] . '.json'))
                {
                    \rename(__DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/menu/' . $item . '/' . $folders[$_GET['new_type']] . '.json', __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/menu/' . $item . '/' . $item . '.json');
                }
            }
        }
        else {
            \mkdir(__DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $item);

            \mkdir(__DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $item . '/.files');
        }

        $order = \array_values(\array_filter(\explode(',', $menu_config['config']['order']), 'strlen'));

        $order[] = $item;

        $menu_config['config']['order'] = \implode(',', $order);

        if ( ! isset($menu_config['config']['default']) || empty($menu_config['config']['default']))
        {
            $menu_config['config']['default'] = $item;
        }

        \file_put_contents($menu_file, \json_encode($menu_config, JSON_PRETTY_PRINT));
    }

    \locate('editor/menu.php?path=' . $path);
}

function changeMenuItemType($path, $item, $new_type)
{
    $path = \ltrim(\rtrim($_GET['path'], '/'), '/');

    $basename = \basename($path);

    $menu_file = __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $basename . '.json';

    if (\in_array($new_type, array(
        'system_page_home',
        'system_page_bio',
        'system_page_portfolio',
        'system_page_shop',
        'system_page_contact',
    )))
    {
        if (\file_exists($menu_file))
        {
            $original = \json_decode(\file_get_contents($menu_file), true);

            if (isset($original['actions'], $original['actions'][$item]))
            {
                $original['actions'][$item] = $new_type;
            }

            \file_put_contents($menu_file, \json_encode($original, JSON_PRETTY_PRINT));
        }
    }

    \locate('editor/menu.php?path=' . $path);
}

function renameMenuItem($path, $old_val, $new_val)
{
    $old_val = \preg_replace('/[^A-Za-z0-9-]/', '', \trim($old_val));

    $new_val = \preg_replace('/[^A-Za-z0-9-]/', '', \trim($new_val));

    if ( ! $new_val)
    {
        // error no new val
    }
    elseif ( ! \strlen($new_val))
    {
        // error no new val
    }
    elseif (\file_exists(__DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $new_val))
    {
        // menu item name already exists
    }
    else {
        $path = \ltrim(\rtrim($_GET['path'], '/'), '/');

        $basename = \basename($path);

        $menu_file = __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $basename . '.json';

        $item_folder = __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $old_val;

        $item_new_folder = __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $new_val;

        $item_folder_sub_menu_file = __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $old_val . '/' . $old_val . '.json';

        $item_folder_new_sub_menu_file = __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $old_val . '/' . $new_val . '.json';

        // rename menu item folder
        if (\file_exists($item_folder))
        {
            \rename($item_folder, $item_new_folder);
        }

        // rename sub menu file if exists
        if (\file_exists($item_folder_sub_menu_file))
        {
            \rename($item_folder_sub_menu_file, $item_folder_new_sub_menu_file);
        }

        // if menu file exists
        if (\file_exists($menu_file))
        {
            $original = \json_decode(\file_get_contents($menu_file), true);

            // change in the order
            $original['config']['order'] = \str_replace($old_val, $new_val, $original['config']['order']);

            // change original
            if ($original['config']['default'] == $old_val)
            {
                $original['config']['default'] = $new_val;
            }

            if (isset($original['actions']))
            {
                $original['actions'][$new_val] = $original['actions'][$old_val];

                unset($original['actions'][$old_val]);
            }

            \file_put_contents($menu_file, \json_encode($original, JSON_PRETTY_PRINT));
        }
    }

    \locate('editor/menu.php?path=' . $path);
}

function setDefaultMenuItem($value)
{
    $path = \ltrim(\rtrim($_GET['path'], '/'), '/');

    $basename = \basename($path);

    $menu_file = __DIR__ . '/../profiles/' . $_SESSION['editing_profile'] . '/' . $path . '/' . $basename . '.json';

    $original = \json_decode(\file_get_contents($menu_file), true);

    $original['config']['default'] = $value;

    \file_put_contents($menu_file, \json_encode($original, JSON_PRETTY_PRINT));

    \locate('editor/menu.php?path=' . $path);
}

function createProfileCopy($value)
{
    global $MASTER;

    $new_name = 'CopyOf' . $value . \mt_rand();

    while (\file_exists(__DIR__ . '/../profiles/' . $new_name))
    {
        $new_name = 'CopyOf' . $value . \mt_rand();
    }

    \full_copy(__DIR__ . '/../profiles/' . $value, __DIR__ . '/../profiles/' . $new_name);

    \locate('home.php');
}

function deleteProfile($value)
{
    global $MASTER;

    if ($value == 'System-Default')
    {
        // system default not allowed
    }
    else {
        \rrmdir(__DIR__ . '/../profiles/' . $value);

        if ($MASTER['global_json']['active_profile'] == $value)
        {
            \setActiveProfile('System-Default');
        }
    }

    \locate('home.php');
}

function setNewProfileName($value, $new_value)
{
    global $MASTER;

    $value = \preg_replace('/[^A-Za-z0-9-]/', '', \trim($value));

    $new_value = \preg_replace('/[^A-Za-z0-9-]/', '', \trim($new_value));

    if (\file_exists(__DIR__ . '/../profiles/' . $new_value))
    {
        $new_value = '(copy) ' . $new_value;
    }

    if ( ! $new_value)
    {
        // error no new val
    }
    elseif ( ! \strlen($new_value))
    {
        // error no new val
    }
    elseif ($value == 'System-Default')
    {
        // system default not allowed
    }
    else {
        \rename(__DIR__ . '/../profiles/' . $value, __DIR__ . '/../profiles/' . $new_value);

        if ($MASTER['global_json']['active_profile'] == $value)
        {
            \setActiveProfile($new_value);
        }
    }

    \locate('home.php');
}

function setActiveProfile($value)
{
    $new_active_profile = \trim($value);

    $settings = \json_decode(\file_get_contents(__DIR__ . '/../profiles/global.json'), true);

    $settings['active_profile'] = $new_active_profile;

    \file_put_contents(__DIR__ . '/../profiles/global.json', \json_encode($settings, JSON_PRETTY_PRINT));

    \locate('home.php');
}

function rrmdir($dir)
{
    if (\is_dir($dir))
    {
        $objects = \scandir($dir);
        foreach ($objects as $object)
        {
            if ($object != '.' && $object != '..')
            {
                if (\is_dir($dir . DIRECTORY_SEPARATOR . $object) && ! \is_link($dir . '/' . $object))
                {
                    \rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                }
                else {
                    \unlink($dir . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
        \rmdir($dir);
    }
}
  function full_copy($source, $target)
  {
      if (\is_dir($source))
      {
          @\mkdir($target);
          $d = \dir($source);
          while (false !== ($entry = $d->read()))
          {
              if ($entry == '.' || $entry == '..')
              {
                  continue;
              }
              $Entry = $source . '/' . $entry;
              if (\is_dir($Entry))
              {
                  \full_copy($Entry, $target . '/' . $entry);

                  continue;
              }
              \copy($Entry, $target . '/' . $entry);
          }

          $d->close();
      }
      else {
          \copy($source, $target);
      }
  }
