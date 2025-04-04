<?php

namespace Inc\AJAX\Modules;

final class Shop
{
    public static $alias;

    public static $config;

    public static $folder;

    public static $folder_url;

    public static function init($shop_alias)
    {
        global $MASTER;

        self::$alias = $shop_alias;

        self::$config = PAGE_DATA[$shop_alias]['page_settings'] ?? false;

        self::$folder = __DIR__ . '/../../../profiles/' . $MASTER['profile_json']['active_profile'] . '/menu/' . $shop_alias;

        self::$folder_url = $MASTER['profile_json']['active_profile_url'] . '/menu/' . $shop_alias;
    }

    public static function items($from = 0)
    {
        $files = \glob(self::$folder . '/.files/*.{' . KNOWN_EXTENSIONS . '}', GLOB_BRACE);

        $items = array();

        foreach ($files as $file_name)
        {
            $name = \basename($file_name);

            if ( ! \file_exists(self::$folder . '/.files/' . $name . '.json'))
            {
                continue;
            }

            $image_data = \json_decode(\file_get_contents(self::$folder . '/.files/' . $name . '.json'), true);

            if (
                ! isset($image_data['properties']['title']) || ! $image_data['properties']['title']
                || ! isset($image_data['properties']['price']) || ! $image_data['properties']['price']
            ) {
                continue;
            }

            $item_image = URL . '/thumb.php?w=500&h=400&src=' . self::$folder_url . '/.files/' . $name;

            $item_title = $image_data['properties']['title'];
            $item_price = $image_data['properties']['price'];
            $item_description = isset($image_data['properties']['description']) && $image_data['properties']['description'] ? $image_data['properties']['description'] : '';

            $items[] = array('title' => $item_title, 'price' => $item_price, 'image' => $item_image, 'description' => $item_description);
        }

        return $items;
    }
}
