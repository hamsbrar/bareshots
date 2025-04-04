<?php

$profiles_path = __DIR__ . '/../../profiles';

if ( ! \file_exists($profiles_path . '/global.json'))
{
    exit('Please set active profile in active file');
}

$MASTER['global_json'] = \json_decode(\file_get_contents($profiles_path . '/global.json'), true);

$active_profile = $MASTER['global_json']['active_profile'];

if ( ! \file_exists($profiles_path . '/' . $active_profile))
{
    exit('Profile not found, please check profiles/active');
}

$profile_path = __DIR__ . '/../../profiles/' . $active_profile;

$MASTER['profile_json'] = \json_decode(\file_get_contents($profile_path . '/config.json'), true);

// -----------------------------------------------------------------

\define('THEMES_PATH', $MASTER['profile_json']['theme']['theme_path']);

\define('THEME', $MASTER['profile_json']['theme']['active_theme']);

\define('TEMPLATES_PATH', $MASTER['profile_json']['theme']['theme_path'] . $MASTER['profile_json']['theme']['active_theme'] . $MASTER['profile_json']['theme']['templates_path']);

// -----------------------------------------------------------------

\define('WEB_NAME', $MASTER['profile_json']['website']['name']);

\define('WEB_TITLE', $MASTER['profile_json']['website']['title']);

\define('LANGUAGE', $MASTER['profile_json']['website']['language']);

\define('VERSION', $MASTER['profile_json']['website']['version']);

\define('MAX_CONTACT_IN_DAY', $MASTER['profile_json']['contact']['max_attempts']);

// -----------------------------------------------------------------

\define('LOGO', URL . '/profiles/' . $active_profile . $MASTER['profile_json']['website']['logo']);

\define('FAVICON', URL . '/profiles/' . $active_profile . $MASTER['profile_json']['website']['favicon']);

// -----------------------------------------------------------------

\define('CSS_MAIN_URL', URL . THEMES_PATH . THEME . $MASTER['profile_json']['assets']['CSS_MAIN_URL']);
\define('CSS_INCLUDES_URL', URL . THEMES_PATH . THEME . $MASTER['profile_json']['assets']['CSS_INCLUDES_URL']);

\define('JS_MAIN_URL', URL . THEMES_PATH . THEME . $MASTER['profile_json']['assets']['JS_MAIN_URL']);
\define('JS_INCLUDES_URL', URL . THEMES_PATH . THEME . $MASTER['profile_json']['assets']['JS_INCLUDES_URL']);

\define('FONTS_MAIN_URL', URL . THEMES_PATH . THEME . $MASTER['profile_json']['assets']['FONTS_MAIN_URL']);
\define('GRAPHICS_MAIN_URL', URL . THEMES_PATH . THEME . $MASTER['profile_json']['assets']['GRAPHICS_MAIN_URL']);

// -----------------------------------------------------------------

\define('MENU_DATA', \internal_parseMenuData($profile_path, 'menu'));

\define('PAGE_DATA', \internal_parsePageData($profile_path, 'menu'));

\define('SOCIAL_DATA', $MASTER['profile_json']['social']);

\define('FOOTER_DATA', $MASTER['profile_json']['footer']);

\define('KNOWN_EXTENSIONS', 'jpg,png,jpeg,gif,ico,webp');

$MASTER['__header_icons'] = $MASTER['profile_json']['icons']['header'];
$MASTER['__footer_icons'] = $MASTER['profile_json']['icons']['footer'];

$MASTER['profile_json']['active_profile'] = $active_profile;
$MASTER['profile_json']['active_profile_url'] = URL . '/profiles/' . $active_profile;

// load language strings from profile
$MASTER = \array_merge($MASTER, \json_decode(\file_get_contents($profile_path . '/languages/' . LANGUAGE . '.json'), true));

// load seo settings
$MASTER = \array_merge($MASTER, $MASTER['profile_json']['seo']);

$MASTER['default_image'] = URL . '/profiles/' . $active_profile . $MASTER['default_image'];

function internal_parseMenuData($path, $internal_path)
{
    $listings = \internal_listInternalDirectories($path . '/' . $internal_path);

    if (empty($listings))
    {
        return false;
    }

    list($orderings, $default_file, $actions) = \internal_parseMenuConfigData($path, $internal_path);

    if ($orderings)
    {
        $listings = $orderings;
    }

    $data = array();

    foreach ($listings as $key => $item)
    {
        $data['items'][$item] = \internal_parseMenuData($path . '/' . $internal_path, $item);
    }

    $data['default'] = $default_file ? $default_file : ($listings[0] ?? false);

    $data['actions'] = $actions ? $actions : false;

    return $data;
}

function internal_parseMenuConfigData($path, $internal_path)
{
    $default_file = $orderings = $actions = $page_settings = false;

    if (\file_exists($path . '/' . $internal_path . '/' . $internal_path . '.json'))
    {
        $settings = \json_decode(\file_get_contents($path . '/' . $internal_path . '/' . $internal_path . '.json'), true);

        if (isset($settings['config']['order']) && ! empty($settings['config']['order']))
        {
            $orderings = array();

            $names = \explode(',', $settings['config']['order']);

            foreach ($names as $key => $item)
            {
                if (\trim($item))
                {
                    if ( ! \file_exists($path . '/' . $internal_path . '/' . \trim($item)))
                    {
                        exit("Invalid order entry '{$item}' in <strong>'" . $path . '/' . $internal_path . '/' . $internal_path . '.json' . '\'</strong>');
                    }

                    $orderings[] = \trim($item);
                }
            }
        }

        if (isset($settings['config']['default']) && ! empty($settings['config']['default']))
        {
            if ( ! \file_exists($path . '/' . $internal_path . '/' . \trim($settings['config']['default'])))
            {
                exit('Invalid default entry \'' . $settings['config']['default'] . '\' in <strong>\'' . $path . '/' . $internal_path . '/' . $internal_path . '.json' . '\'</strong>');
            }

            $default_file = \trim($settings['config']['default']);
        }

        if (isset($settings['actions']))
        {
            $actions = $settings['actions'];
        }

        if (isset($settings['page_settings']))
        {
            $page_settings = $settings['page_settings'];
        }
    }

    return array($orderings, $default_file, $actions, $page_settings);
}

function internal_parsePageData($path, $internal_path)
{
    $listings = \internal_listInternalDirectories($path . '/' . $internal_path);

    $data = array();

    foreach ($listings as $key => $item)
    {
        $data[$item] = \internal_parsePageData($path . '/' . $internal_path, $item);
    }

    if (\file_exists($path . '/' . $internal_path . '/page_settings.json'))
    {
        $settings = \json_decode(\file_get_contents($path . '/' . $internal_path . '/page_settings.json'), true);
    }
    else {
        $settings = false;
    }

    $data['page_settings'] = $settings ? $settings['page_settings'] : false;

    return $data;
}

function internal_listInternalDirectories($path)
{
    $data = array();

    foreach (\glob("{$path}/*", GLOB_ONLYDIR) as $dir)
    {
        $data[] = \basename($dir);
    }

    return \array_values($data);
}

function internal_parseSocialData($social_file)
{
    $social = array();

    if (\file_exists($social_file))
    {
        $fp = \fopen($social_file, 'r');

        if ($fp)
        {
            $unparsed_items = \explode("\n", \fread($fp, \filesize($social_file)));
        }

        \fclose($fp);

        foreach ($unparsed_items as $key => $item)
        {
            if (\trim($item))
            {
                $splits = \explode(' ', \trim($item));

                if (\count($splits) == 2)
                {
                    $social[\trim($splits[0])] = \trim($splits[1]);
                }
            }
        }
    }

    return $social;
}

function internal_parseDefaultFile($dir_path)
{
    if (\file_exists($dir_path . '/default'))
    {
        $contents = \file_get_contents($dir_path . '/default');

        if (\trim($contents))
        {
            if ( ! \file_exists($dir_path . '/' . \trim($contents)))
            {
                exit("Invalid default entry '{$contents}' in '{$dir_path} default' file");
            }

            return \trim($contents);
        }
    }

    return false;
}

function internal_parseOrderFile($dir_path)
{
    if (\file_exists($dir_path . '/order'))
    {
        $fp = \fopen($dir_path . '/order', 'r');

        if ($fp)
        {
            $unparsed_items = \explode("\n", \fread($fp, \filesize($dir_path . '/order')));
        }

        \fclose($fp);

        $names = array();

        foreach ($unparsed_items as $key => $item)
        {
            if (\trim($item))
            {
                if ( ! \file_exists($dir_path . '/' . \trim($item)))
                {
                    exit("Invalid order entry '{$item}' in '{$dir_path} order' file");
                }

                $names[] = \trim($item);
            }
        }

        return $names;
    }

    return false;
}
