<?php

declare(strict_types=1);

namespace App\Model;

final readonly class Identity
{
    public function __construct(
        public string $id,
        public string $displayName,
        public string $email,
    ) {}
}
