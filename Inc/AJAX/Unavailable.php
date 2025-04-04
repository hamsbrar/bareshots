<?php

namespace Inc\AJAX;

use Inc\System\AJAXResponder;
use Inc\System\Renderer;

final class Unavailable
{
    public static function respond()
    {
        global $MASTER;

        Renderer::init();

        AJAXResponder::setWarning($MASTER['_unavailable_request']);

        AJAXResponder::respond();
    }
}
