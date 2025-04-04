<?php

namespace Inc\System\Utilities;

final class Perform
{
    public static function associateArray($assoc, $params)
    {
        $return = array();

        foreach ($params as $key => $value)
        {
            if ( ! empty($value) && isset($assoc[$key]))
            {
                $return[$assoc[$key]] = $value;
            }
        }

        return ( ! empty($return)) ? $return : array();
    }

    public static function createArray($des, $string)
    {
        return ( ! empty($string)) ? \array_values(\array_filter(\explode($des, $string), 'strlen')) : null;
    }

    public static function decode($codec, $value)
    {
        global $codecs;

        $characters = \str_split(\strrev($value));

        $decoded_version = '';

        foreach ($characters as $char)
        {
            $decoded_version = $decoded_version . \array_search($char, $codecs[$codec]);
        }

        return $decoded_version;
    }

    public static function encode($codec, $value)
    {
        global $codecs;

        $characters = \str_split($value);

        $encoded_version = '';

        foreach ($characters as $char)
        {
            $encoded_version = $encoded_version . $codecs[$codec][$char];
        }

        return \strrev($encoded_version);
    }

    public static function redirect($domain, $path)
    {
        \header('LOCATION: ' . $domain . $path);
    }

    public static function sanitize($input, $strip_only = false)
    {
        if ($strip_only)
        {
            return \strip_tags($input);
        }

        return \strip_tags(\htmlentities($input, ENT_QUOTES));
    }

    public static function slugify($text)
    {
        $text = \preg_replace('~[^\pL\d]+~u', '-', $text);

        $text = \iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        $text = \preg_replace('~[^-\w]+~', '', $text);

        $text = \trim($text, '-');

        $text = \preg_replace('~-+~', '-', $text);

        $text = \strtolower($text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }

    public static function startsWith($string, $starts_with)
    {
        return \strpos($string, $starts_with) === 0;
    }
}
