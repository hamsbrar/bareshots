<?php

namespace Inc\System;

use Inc\System\Utilities\Path;
use Inc\System\Utilities\Perform;

final class Renderer
{
    public static $content = false;

    public static $meta_tags = 'default';

    public static function init()
    {
        self::preparePaths();

        self::prepareMetaTags(self::$meta_tags);
    }

    public static function insert($key, $value)
    {
        global $MASTER;

        $MASTER['insert-' . $key] = $value;
    }

    public static function open($template_name)
    {
        $template_file = Path::template($template_name);

        $template_opened = \fopen($template_file, 'r');

        $template_contents = @\fread($template_opened, \filesize($template_file));

        \fclose($template_opened);

        return $template_contents;
    }

    public static function openParse($template_name)
    {
        return self::parse(self::open($template_name));
    }

    // direct output functions

    public static function output($parsed_content)
    {
        echo $parsed_content;
    }

    public static function outputObject($object)
    {
        echo \json_encode($object);
    }

    public static function page()
    {
        if ( ! self::$content)
        {
            self::setPageContent('404 - Page not found');
        }

        self::render('wrapper');
    }

    public static function parse($template_contents)
    {
        global $MASTER;

        $template_contents = \preg_replace_callback(
            '/{\$MASTER->(.+?)}/i',
            function ($matches) use ($MASTER)
            {
                if (isset($MASTER[$matches[1]]))
                {
                    return $MASTER[$matches[1]];
                }

                return '[not matched] ' . $matches[1];
            },
            $template_contents
        );

        return $template_contents;
    }

    public static function prepareMetaTags($prefix = 'default')
    {
        global $MASTER;

        $MASTER['set_web_name'] = Perform::sanitize(WEB_NAME);
        $MASTER['set_title'] = Perform::sanitize($MASTER[$prefix . '_title']);
        $MASTER['set_type'] = Perform::sanitize($MASTER[$prefix . '_type']);
        $MASTER['set_image'] = Perform::sanitize($MASTER[$prefix . '_image']);
        $MASTER['set_site_name'] = Perform::sanitize($MASTER[$prefix . '_site_name']);
        $MASTER['set_description'] = Perform::sanitize($MASTER[$prefix . '_description']);
        $MASTER['set_image_width'] = Perform::sanitize($MASTER[$prefix . '_image_width']);
        $MASTER['set_image_height'] = Perform::sanitize($MASTER[$prefix . '_image_height']);
    }

    public static function preparePaths()
    {
        global $MASTER;

        $MASTER['set_url'] = Perform::sanitize(URL);
        $MASTER['set_logo'] = Perform::sanitize(LOGO);
        $MASTER['set_favicon'] = Perform::sanitize(FAVICON);
        $MASTER['set_cache_code'] = Perform::sanitize('?' . VERSION);

        $MASTER['set_fonts_url'] = Perform::sanitize(FONTS_MAIN_URL);
        $MASTER['set_graphics_url'] = Perform::sanitize(GRAPHICS_MAIN_URL);

        $MASTER['set_css_main'] = Perform::sanitize(CSS_MAIN_URL);
        $MASTER['set_css_includes'] = Perform::sanitize(CSS_INCLUDES_URL);

        $MASTER['set_js_main'] = Perform::sanitize(JS_MAIN_URL);
        $MASTER['set_js_includes'] = Perform::sanitize(JS_INCLUDES_URL);
    }

    public static function render($template_name)
    {
        self::output(
            self::parse(
                self::open(
                    $template_name
                )
            )
        );
    }

    public static function setMetaTags($prefix)
    {
        global $MASTER;

        if (isset($MASTER[$prefix . '_title']))
        {
            self::$meta_tags = $prefix;
        }

        self::prepareMetaTags(self::$meta_tags);
    }

    public static function setPageContent($content)
    {
        global $MASTER;

        self::$content = true;

        $MASTER['set_page_content'] = $content;
    }
}
