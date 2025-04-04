<?php

// below are proccessed routes,
$ROUTES['HIGH_PERFORMANT'] = array(
    'ajax'      => array(
        'app'      => array(
            ':value:'   => 'AJAX\\Pages\\AppPage',

            ':blank:'   => 'AJAX\\Pages\\AppPage',
        ),

        'page'      => array(
            ':value:'   => 'AJAX\\Pages\\Page',

            ':blank:'   => 'AJAX\\Pages\\Page',
        ),

        'submit'    => array(
            'contact'    => array(
                ':value:'   => 'AJAX\\Submit\\Contact',

                ':blank:'   => 'AJAX\\Submit\\Contact',
            ),

            ':value:'   => 'AJAX\\Unavailable',

            ':blank:'   => 'AJAX\\Unavailable',
        ),

        ':value:'   => 'AJAX\\Unavailable',

        ':blank:'   => 'AJAX\\Unavailable',
    ),

    ':value:'   => array(
        ':value:' => 'Pages\\AppPage',
        ':blank:' => 'Pages\\AppPage',
    ),

    ':blank:'   => 'Pages\\AppPage',
);
