<?php

$dir = __DIR__ . '/../theme-builder/includes/graphics/social/colored/*.png';

$files = \glob($dir, GLOB_BRACE);

$array = array();

foreach ($files as $file)
{
    $array[\basename($file)] = '';
}

\file_put_contents('social.json', \json_encode($array, JSON_PRETTY_PRINT));
