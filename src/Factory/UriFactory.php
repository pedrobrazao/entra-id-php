<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Http\Message\UriInterface;
use Slim\Psr7\Uri;

final class UriFactory
{
    public static function create(string $uri): UriInterface
    {
        parse_str($uri, $parts);

        return new Uri(
            scheme: $parts[PHP_URL_SCHEME] ?? 'http',
            host: $parts[PHP_URL_HOST] ?? 'localhost',
            port: $parts[PHP_URL_PORT] ?? null,
            path: $parts[PHP_URL_PATH] ?? '/',
            query: $parts[PHP_URL_QUERY] ?? '',
            fragment: $parts[PHP_URL_FRAGMENT] ?? '',
            user: $parts[PHP_URL_USER] ?? '',
            password: $parts[PHP_URL_PASS] ?? '',
        );
    }
}
