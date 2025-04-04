<?php

namespace Inc\System;

final class AJAXResponder
{
    public static $data = '';

    public static $error = '';

    public static $message = '';

    public static $warning = '';

    public static function respond()
    {
        $response = array('error' => self::$error, 'warning' => self::$warning, 'data' => self::$data, 'message' => self::$message);

        echo \json_encode($response);
    }

    public static function setData($data)
    {
        self::$data = $data;
    }

    public static function setError($error)
    {
        Renderer::insert('error_message', $error);

        self::$error = Renderer::openParse('alerts/error');
    }

    public static function setMessage($message)
    {
        Renderer::insert('message', $message);

        self::$message = Renderer::openParse('alerts/message');
    }

    public static function setSuccess($message)
    {
        Renderer::insert('message', $message);

        self::$message = Renderer::openParse('alerts/success');
    }

    public static function setWarning($warning)
    {
        Renderer::insert('warning_message', $warning);

        self::$message = Renderer::openParse('alerts/warning');
    }
}
