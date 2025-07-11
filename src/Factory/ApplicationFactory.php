<?php

declare(strict_types=1);

namespace App\Factory;

use App\Handler\Admin\ApplicationGetHandler;
use App\Handler\Admin\ApplicationListHandler;
use App\Handler\Admin\UserGetHandler;
use App\Handler\Admin\UserListHandler;
use App\Handler\HomeHandler;
use App\Middleware\IdentityMiddleware;
use Mezzio\Session\SessionMiddleware;
use Mezzio\Session\SessionPersistenceInterface;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

final readonly class ApplicationFactory
{
    public function __construct(private ContainerInterface $container) {}

    /** @phpstan-ignore missingType.generics */
    public function create(): App
    {
        AppFactory::setContainer($this->container);

        $app = AppFactory::create();

        $app->add(new IdentityMiddleware());
        $app->add(TwigMiddleware::create($app, $app->getContainer()->get(Twig::class)));
        $app->add(new SessionMiddleware($app->getContainer()->get(SessionPersistenceInterface::class)));
        $app->addRoutingMiddleware();

        $settings = $this->container->get('settings') ?? [];
        $errorMiddleware = $app->addErrorMiddleware($settings['displayErrorDetails'] ?? false, $settings['logErrors'] ?? true, $settings['logErrorDetails'] ?? true);

        $app->get('/', HomeHandler::class)->setName(HomeHandler::NAME);

        $app->group('/admin', function (RouteCollectorProxy $group): void {
            $group->get('/users', UserListHandler::class)->setName(UserListHandler::NAME);
            $group->get('/users/{id}', UserGetHandler::class)->setName(UserGetHandler::NAME);
            $group->get('/applications', ApplicationListHandler::class)->setName(ApplicationListHandler::NAME);
            $group->get('/applications/{id}', ApplicationGetHandler::class)->setName(ApplicationGetHandler::NAME);
        });

        return $app;
    }
}
