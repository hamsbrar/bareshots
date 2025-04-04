<?php

namespace Inc\System\Utilities;

final class Path
{
    public static $root = false;

    public static function construct($file_name)
    {
        $path = './';

        while ( ! \file_exists($path . $file_name))
        {
            if (\strlen($path) > 150)
            {
                exit('Unexpected error in file system');
            }

            $path = $path . '../';
        }

        return self::$root = $path;
    }

    public static function constructRootPath()
    {
        return self::construct('index.php');
    }

    public static function root()
    {
        if ( ! self::$root)
        {
            self::constructRootPath();
        }

        return self::$root;
    }

    public static function template($template_name)
    {
        return self::root() . TEMPLATES_PATH . $template_name . '.html';
    }
}
