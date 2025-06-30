<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Factory\ContainerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

abstract class IntegrationTestCase extends TestCase
{
    private ?ContainerInterface $container = null;

    protected function getContainer(): ContainerInterface
    {
        if (null === $this->container) {
            $definitions = include __DIR__.'/../../config/container.php';
            $this->container = (new ContainerFactory($definitions))->create();
        }

        return $this->container;
    }
}
