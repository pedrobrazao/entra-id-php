<?php

declare(strict_types=1);

namespace App\Factory;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final readonly class ContainerDecorator implements ContainerInterface
{
    public function __construct(private ContainerInterface $container) {}

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }

    public function get(string $id)
    {
        return $this->container->get($id);
    }

    /**
     * @return array<string, scalar|scalar[]>
     */
    public function getSettings(): array
    {
        return (array) $this->get('settings');
    }

    public function getLogger(): LoggerInterface
    {
        $settings = $this->getSettings();
        $loggerSettings = $settings['logger'] ?? [];
        $logger = new Logger($loggerSettings['name'] ?? 'App');
        $handler = new StreamHandler($loggerSettings['path'] ?? sys_get_temp_dir(), $loggerSettings['level'] ?? Level::Warning);
        $logger->pushHandler($handler);

        return $logger;
    }
}
