<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

// executes the "php bin/console doctrine:fixture:load" command
passthru(
    sprintf(
        'php "%s/../bin/console" doctrine:fixture:load --purge-with-truncate -n',
        __DIR__
    )
);

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}
