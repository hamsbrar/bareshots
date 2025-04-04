<?php

namespace Inc\Pages;

use Inc\System\Router;
use Inc\System\Renderer;

final class AppPage
{
    public static function respond()
    {
        global $ROUTES;

        Renderer::init();

        Renderer::insert('load_path', 'ajax/app');

        // legacy router, dropped for assoc error
        // Renderer::insert('app_route_1', $ROUTES['CURRENT_ROUTE_SPLITS'][0] ? $ROUTES['CURRENT_ROUTE_SPLITS'][0] : MENU_DATA['default']);
        // Renderer::insert('app_route_2', Router::getSplit(1));

        Renderer::insert('app_route_1', $ROUTES['CURRENT_ROUTE_SPLITS'][0] ? $ROUTES['CURRENT_ROUTE_SPLITS'][0] : MENU_DATA['default']);

        Renderer::insert('app_route_2', $ROUTES['CURRENT_ROUTE_SPLITS'][1] ?? '');

        Renderer::insert('loader', Renderer::openParse('loaders/app_page'));

        Renderer::setPageContent(
            Renderer::openParse('skelton')
        );

        Renderer::page();
    }
}
