<?php

use App\Factory\ContainerDecorator;
use Psr\Log\LoggerInterface;

return file_exists(__DIR__.'/container.local.php')
    ? include __DIR__.'/container.local.php'
    : [
        'settings' => include __DIR__.'/settings.php',
        LoggerInterface::class => function (ContainerDecorator $c) {
            return $c->getLogger();
        },
    ];
