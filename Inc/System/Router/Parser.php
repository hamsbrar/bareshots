<?php

namespace Inc\System\Router;

use Debug;
use Inc\System\Utilities\Perform;

final class Parser
{
    public static function optimizeRoutes($routes_array, $nearest_blank_request_path = null, $neareast_value_request = null)
    {
        if (isset($routes_array[':blank:']))
        {
            $nearest_blank_request_path = $routes_array[':blank:'];
        }
        else {
            $routes_array[':blank:'] = $neareast_value_request ?? $nearest_blank_request_path;
        }

        if ( ! isset($routes_array[':value:']))
        {
            $routes_array[':value:'] = $nearest_blank_request_path;
        }

        foreach ($routes_array as $route_key => $request_path)
        {
            if (\is_array($request_path))
            {
                $routes_array[$route_key] = self::optimizeRoutes($request_path, $nearest_blank_request_path, $neareast_value_request);
            }
        }

        return $routes_array;
    }

    public static function parseRoute($route, $request_path, $routes_array)
    {
        $route_splits = Perform::createArray('/', $route);

        switch (\count($route_splits))
        {
            case 0: $routes_array[':blank:'] = $request_path;

break;

            case 1: $routes_array[$route_splits[0]] = $request_path;

break;

            default:
                $leftover_route = \preg_replace('/' . \preg_quote($route_splits[0], '/') . '/', '', $route, 1);

                if ( ! isset($routes_array[$route_splits[0]]))
                {
                    $routes_array[$route_splits[0]] = self::parseRoute($leftover_route, $request_path, array());
                }
                else {
                    $parsed_array = self::parseRoute($leftover_route, $request_path, array());

                    foreach ($parsed_array as $route_key_inner => $request_path_inner)
                    {
                        if (isset($routes_array[$route_splits[0]][$route_key_inner]))
                        {
                            $sub_routed_routes = array(
                                ':value:' => $routes_array[$route_splits[0]][$route_key_inner],
                                ':blank:' => $routes_array[$route_splits[0]][$route_key_inner],
                            );

                            $routes_array[$route_splits[0]][$route_key_inner] = \array_merge(
                                $parsed_array[$route_key_inner],
                                $sub_routed_routes,
                            );
                        }
                        else {
                            $routes_array[$route_splits[0]][$route_key_inner] = $request_path_inner;
                        }
                    }
                }

            break;
        }

        return $routes_array;
    }

    public static function parseRoutes()
    {
        global $ROUTES;

        $ROUTES['ON_FLY_HIGH_PERFORMANT'] = array();

        foreach ($ROUTES['PREMATURE'] as $route => $request_path)
        {
            $ROUTES['ON_FLY_HIGH_PERFORMANT'] = self::parseRoute($route, $request_path, $ROUTES['ON_FLY_HIGH_PERFORMANT']);
        }

        $ROUTES['ON_FLY_HIGH_PERFORMANT'] = self::optimizeRoutes($ROUTES['ON_FLY_HIGH_PERFORMANT']);

        // Debug::log('Below are the high performance routes for the current status of system', $ROUTES['ON_FLY_HIGH_PERFORMANT']);

        return $ROUTES['ON_FLY_HIGH_PERFORMANT'];
    }
}
