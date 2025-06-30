<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

final readonly class ApplicationFactory
{
    public function __construct(private ContainerInterface $container) {}

    /** @phpstan-ignore missingType.generics */
    public function create(): App
    {
        AppFactory::setContainer($this->container);

        $app = AppFactory::create();

        $app->addRoutingMiddleware();

        $settings = $this->container->get('settings') ?? [];
        $errorMiddleware = $app->addErrorMiddleware($settings['displayErrorDetails'] ?? false, $settings['logErrors'] ?? true, $settings['logErrorDetails'] ?? true);

        return $app;
    }
}
