<?php

require 'lib/tracy/tracy.php';

\Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT, __DIR__ . '/logs/errors');

// \Tracy\Debugger::enable(\Tracy\Debugger::PRODUCTION, __DIR__ . '/logs');

\Tracy\Debugger::$scream = true;

\Tracy\Debugger::$showBar = false;