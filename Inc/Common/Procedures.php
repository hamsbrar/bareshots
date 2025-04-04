<?php

namespace Inc\Common;

use Inc\System\Renderer;

final class Procedures
{
    public static function generateFooterData()
    {
        return \json_encode(FOOTER_DATA);
    }

    public static function generateMenuData()
    {
        return \json_encode(MENU_DATA);
    }

    public static function generateSocialData()
    {
        return \json_encode(SOCIAL_DATA);
    }

    public static function getPageNav($page_name, $sub_alias, $workout = 'load_page')
    {
        $content = '';

        foreach (MENU_DATA['items'][$page_name]['items'] as $name => $data)
        {
            Renderer::insert('item_name', $name);

            Renderer::insert('item_action', "{$workout}('{$page_name}', '{$name}')");

            Renderer::insert('item_active', $sub_alias == $name ? 'active' : '');

            $content .= Renderer::openParse('partials/nav_item');
        }

        if ( ! empty($content))
        {
            Renderer::insert('items', $content);

            return Renderer::openParse('partials/navigation');
        }

        return '';
    }

    public static function getPageQuote($page_settings)
    {
        global $MASTER;

        if (isset($page_settings['page_quote']) && \trim($page_settings['page_quote']))
        {
            Renderer::insert('page_quote', $page_settings['page_quote']);

            return Renderer::openParse('partials/page_quote');
        }

        return '';
    }

    public static function getPageTitle($page_settings)
    {
        global $MASTER;

        if (isset($page_settings['page_title']) && \trim($page_settings['page_title']))
        {
            Renderer::insert('page_title', $page_settings['page_title']);

            Renderer::insert('page_description', $page_settings['page_description']);

            Renderer::insert('hide_desktop', $page_settings['hide_page_title_on_desktop']);

            return Renderer::openParse('partials/page_title');
        }

        return '';
    }

    public static function getRouteAction($route, $data)
    {
        if (isset($data['actions']) && $data['actions'] && isset($data['actions'][$route]) && $data['actions'][$route])
        {
            return $data['actions'][$route];
        }

        return false;
    }

    public static function matchSimpleHash($hashed_string, $from)
    {
        return \md5($from) == $hashed_string;
    }
}
