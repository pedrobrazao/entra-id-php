<?php

declare(strict_types=1);

namespace App\Service;

use Microsoft\Graph\Generated\Models\User;
use Microsoft\Graph\GraphServiceClient;

final readonly class UserService
{
    public function __construct(
        private GraphServiceClient $client
    ) {}

    /**
     * @return User[]
     */
    public function listUsers(): array
    {
        $response = $this->client->users()->get()->wait();

        if (null === $response || null === $users = $response->getValue()) {
            return [];
        }

        return $users;
    }

    public function getUserById(string $id): ?User
    {
        return $this->client->users()->byUserId($id)->get()->wait();

    }
}
