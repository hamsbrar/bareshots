<?php

namespace Inc\AJAX\Modules;

final class Portfolio
{
    public static $alias;

    public static $config;

    public static $folder;

    public static $folder_url;

    public static $set_default_sub_alias = false;

    public static $sub_alias;

    public static $sub_folder_urls = array();

    public static $sub_folders = array();

    public static $sub_valid_folders;

    public static function images()
    {
        $files = \glob(self::$sub_folders[self::$sub_alias] . '/.files/*.{' . KNOWN_EXTENSIONS . '}', GLOB_BRACE);

        $images = array();

        if ( ! empty($files))
        {
            if (self::$config['reverse_order'])
            {
                $files = \array_reverse($files);
            }

            foreach ($files as $key => $file)
            {
                $name = \basename($file);

                if (\file_exists(self::$sub_folders[self::$sub_alias] . '/.files/' . $name))
                {
                    $images[] = array(
                        'image_name' => $name,

                        'image_src' => self::$sub_folder_urls[self::$sub_alias] . '/.files/' . $name,
                    );
                }
            }

            return $images;
        }

        return false;
    }

    public static function init($portfolio_alias, $sub_alias)
    {
        global $MASTER;

        self::$alias = $portfolio_alias;

        self::$config = PAGE_DATA[$portfolio_alias]['page_settings'] ?? false;

        if ((empty($sub_alias) || ! $sub_alias))
        {
            return self::$set_default_sub_alias = MENU_DATA['items'][$portfolio_alias]['default'];
        }

        self::$sub_alias = $sub_alias;

        self::$folder = __DIR__ . '/../../../profiles/' . $MASTER['profile_json']['active_profile'] . '/menu/' . $portfolio_alias;

        self::$folder_url = $MASTER['profile_json']['active_profile_url'] . '/menu/' . $portfolio_alias;

        self::$sub_valid_folders = \array_keys(MENU_DATA['items'][$portfolio_alias]['items']);

        foreach (self::$sub_valid_folders as $key => $name)
        {
            self::$sub_folders[$name] = self::$folder . '/' . $name;

            self::$sub_folder_urls[$name] = $MASTER['profile_json']['active_profile_url'] . '/menu/' . $portfolio_alias . '/' . $name;
        }
    }
}
