<?php

declare(strict_types=1);

namespace App\Handler\Admin;

use App\Service\ApplicationService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

final readonly class ApplicationGetHandler implements RequestHandlerInterface
{
    public const NAME = 'admin/application_get';
    private const TEMPLATE = 'admin/application_get.html.twig';

    public function __construct(
        private ApplicationService $applicationService,
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = RouteContext::fromRequest($request)->getRoute()->getArgument('id');

        if (null === $id || null === $application = $this->applicationService->getApplicationById($id)) {
            throw new \InvalidArgumentException('Application not found.');
        }

        $properties = [];

        foreach (array_keys($application->getFieldDeserializers()) as $field) {
            $method = 'get'.ucfirst($field);
            if (method_exists($application, $method) && null !== ($value = $application->{$method}()) && is_object($value) && method_exists($value, 'getFieldDeserializers')) {
                $properties[$field] = $value;
            }
        }

        return Twig::fromRequest($request)->render(new Response(), self::TEMPLATE, [
            'application' => $application,
            'properties' => $properties,
        ]);
    }
}
