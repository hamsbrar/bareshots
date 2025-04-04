<?php

namespace Inc\System;

use Inc\System\Router\Parser;
use Inc\System\Utilities\Perform;

final class Router
{
    public static $request_routes;

    public static function availableRoutes()
    {
        global $ROUTES;

        if (isset($ROUTES['HIGH_PERFORMANT']))
        {
            return $ROUTES['HIGH_PERFORMANT'];
        }

        if (isset($ROUTES['PREMATURE']))
        {
            return Parser::parseRoutes();
        }
    }

    public static function init()
    {
        global $ROUTES;

        self::$request_routes = array();

        if (IS_PATH)
        {
            $url = \strtolower(\str_replace(PATH, '', \str_replace('%20', ' ', $_SERVER['REQUEST_URI'])));
        }
        else {
            $url = \strtolower(\str_replace('%20', ' ', $_SERVER['REQUEST_URI']));
        }

        $ROUTES['CURRENT_ROUTE_SPLITS'] = Perform::associateArray(
            array(
                '0',
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
            ),
            Perform::createArray('/', $url)
        );

        $ROUTES['CURRENT_ROUTE_COMPLETED'] = $url;
    }

    public static function route()
    {
        global $ROUTES;

        self::init();

        $ROUTES['CURRENT_ROUTE_SELECTED'] = '';
        $ROUTES['CURRENT_ROUTE_SELECTED_VALUES'] = array();

        if (\count($ROUTES['CURRENT_ROUTE_SPLITS']) == 0)
        {
            $ROUTES['CURRENT_ROUTE_SPLITS'] = array('');
        }

        self::selectRoute(0, self::availableRoutes());
    }

    public static function selectRoute($route_index, $from_routes)
    {
        global $ROUTES;

        $route = $ROUTES['CURRENT_ROUTE_SPLITS'][$route_index] ?? '';

        if (isset($from_routes[$route]))
        {
            $selected_route = $from_routes[$route];
        }
        elseif (empty($route))
        {
            $selected_route = $from_routes[':blank:'];
        }
        else {
            $ROUTES['CURRENT_ROUTE_SELECTED_VALUES'][($ROUTES['CURRENT_ROUTE_SPLITS'][$route_index - 1] ?? 'domain_followed')] = \trim($route);

            $selected_route = $from_routes[':value:'];
        }

        if (\is_array($selected_route))
        {
            return self::selectRoute($route_index + 1, $selected_route);
        }

        $ROUTES['CURRENT_ROUTE_SELECTED'] = $selected_route;
    }
}
