    <?php

use App\Factory\ApplicationFactory;
use App\Factory\ContainerFactory;

require __DIR__.'/../vendor/autoload.php';

$container = (new ContainerFactory(include __DIR__.'/../config/container.php'))->create();
$app = (new ApplicationFactory($container))->create();

$app->run();
