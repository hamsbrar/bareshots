<?php

namespace Inc\AJAX\Pages;

use Inc\System\AJAXResponder;
use Inc\System\Renderer;
use Inc\Common\Procedures;

final class AppPage
{
    public static function respond()
    {
        Renderer::init();

        Renderer::insert('menu_data', Procedures::generateMenuData());

        Renderer::insert('social_data', Procedures::generateSocialData());

        Renderer::insert('footer_data', Procedures::generateFooterData());

        Renderer::insert('app_header', Renderer::openParse('partials/header'));

        Renderer::insert('app_route_1', $_POST['route_1'] ? $_POST['route_1'] : MENU_DATA['default']);

        Renderer::insert('app_route_2', $_POST['route_2'] ? $_POST['route_2'] : '');

        Renderer::insert('app_footer', Renderer::openParse('partials/footer'));

        AJAXResponder::setData(
            Renderer::openParse('pages/app_page')
        );

        AJAXResponder::respond();
    }
}
