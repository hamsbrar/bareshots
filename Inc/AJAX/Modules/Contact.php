<?php

namespace Inc\AJAX\Modules;

final class Contact
{
    public static $alias;

    public static $config;

    public static $folder;

    public static $folder_url;

    public static function init($contact_alias)
    {
        global $MASTER;

        self::$alias = $contact_alias;

        self::$config = PAGE_DATA[$contact_alias]['page_settings'] ?? false;

        self::$folder = __DIR__ . '/../../../profiles/' . $MASTER['profile_json']['active_profile'] . '/menu/' . $contact_alias;

        self::$folder_url = $MASTER['profile_json']['active_profile_url'] . '/menu/' . $contact_alias;
    }
}
