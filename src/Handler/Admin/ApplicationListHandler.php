<?php

declare(strict_types=1);

namespace App\Handler\Admin;

use App\Service\ApplicationService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

final readonly class ApplicationListHandler implements RequestHandlerInterface
{
    public const NAME = 'admin/application_list';
    private const TEMPLATE = 'admin/application_list.html.twig';

    public function __construct(
        private ApplicationService $applicationService,
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $applications = $this->applicationService->listApplications();

        return Twig::fromRequest($request)->render(new Response(), self::TEMPLATE, [
            'applications' => $applications,
        ]);
    }
}
