<?php

namespace Inc\AJAX\Modules;

final class Bio
{
    public static $alias;

    public static $config;

    public static $folder;

    public static $folder_url;

    public static function image()
    {
        $files = \glob(self::$folder . '/.files/*.{' . KNOWN_EXTENSIONS . '}', GLOB_BRACE);

        if ( ! empty($files))
        {
            $name = \basename($files[0]);

            if (\file_exists(self::$folder . '/.files/' . $name))
            {
                return URL . '/thumb.php?w=800&h=800&src=' . self::$folder_url . '/.files/' . $name;
            }
        }

        return false;
    }

    public static function init($bio_alias)
    {
        global $MASTER;

        self::$alias = $bio_alias;

        self::$config = PAGE_DATA[$bio_alias]['page_settings'] ?? false;

        self::$folder = __DIR__ . '/../../../profiles/' . $MASTER['profile_json']['active_profile'] . '/menu/' . $bio_alias;

        self::$folder_url = $MASTER['profile_json']['active_profile_url'] . '/menu/' . $bio_alias;
    }
}
