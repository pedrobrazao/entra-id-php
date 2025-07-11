<?php

declare(strict_types=1);

namespace App\Service;

use Microsoft\Graph\Generated\Models\Application;
use Microsoft\Graph\GraphServiceClient;

final readonly class ApplicationService
{
    public function __construct(
        private GraphServiceClient $client
    ) {}

    /**
     * @return Application[]
     */
    public function listApplications(): array
    {
        $response = $this->client->applications()->get()->wait();

        if (null === $response || null === $applications = $response->getValue()) {
            return [];
        }

        return $applications;
    }

    public function getapplicationById(string $id): ?Application
    {
        return $this->client->applications()->byapplicationId($id)->get()->wait();

    }
}
