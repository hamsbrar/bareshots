<?php

function locate($to)
{
    \header('location: ' . PANEL_URL . '/' . $to);
}

function getPossibleValues($config)
{
    switch ($config['possible_values_generator_type'])
    {
        case 'directory':
            $values = \internal_listInternalDirectories(__DIR__ . '/../..' . \parseSystemVariables($config['possible_values_generator_variable']));

            $to_return_values = array();

            foreach ($values as $value)
            {
                $to_return_values[$value] = $value;
            }

            return $to_return_values;

        case 'files':
            $values = \glob(__DIR__ . '/../..' . \parseSystemVariables($config['possible_values_generator_variable']), GLOB_BRACE);

            $to_return_values = array();

            foreach ($values as $value)
            {
                $parsed = '/brand/' . \basename($value);

                $to_return_values[$parsed] = $parsed;
            }

            return $to_return_values;

        case 'given':
            return $config['possible_values_generator_variable'];
    }
}

function parseSystemVariables($string)
{
    return \str_replace(
        array(
            '%editing_profile%',
        ),
        array(
            $_SESSION['editing_profile'],
        ),
        $string
    );
}
