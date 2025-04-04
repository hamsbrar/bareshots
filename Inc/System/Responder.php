<?php

namespace Inc\System;

final class Responder
{
    public static function respond($route)
    {
        $pipes = \call_user_func(array('Inc\\' . $route, 'respond'));
    }
}
