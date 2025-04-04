<?php

namespace Inc\AJAX\Pages;

use Inc\System\AJAXResponder;
use Inc\System\Renderer;
use Inc\System\Settings;
use Inc\Common\Procedures;

final class Page
{
    public static function respond()
    {
        global $MASTER;

        Renderer::init();

        $route_1 = isset($_POST['route_1']) && $_POST['route_1'] ? $_POST['route_1'] : false;

        $route_2 = isset($_POST['route_2']) && $_POST['route_2'] ? $_POST['route_2'] : false;

        $route_1_action = Procedures::getRouteAction($route_1, MENU_DATA);

        $route_2_action = Procedures::getRouteAction($route_2, MENU_DATA['items'][$route_1] ?? false);

        if ( ! $route_1_action || ! isset(Settings::$system_actions[$route_1_action]))
        {
            AJAXResponder::setWarning($MASTER['_unavailable_request']);
        }
        else {
            \call_user_func(
                array('Inc\\AJAX\\SystemActions', Settings::$system_actions[$route_1_action],
                ),
                array(
                    'route_1'  => $route_1,
                    'route_2'  => $route_2,
                    'action_1' => $route_1_action,
                    'action_2' => $route_2_action,
                )
            );
        }

        AJAXResponder::respond();
    }
}
