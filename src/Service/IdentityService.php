<?php

declare(strict_types=1);

namespace App\Service;

use App\Factory\UriFactory;
use App\Model\Identity;
use Mezzio\Session\SessionInterface;
use Microsoft\Graph\Generated\Models\Application;
use Microsoft\Graph\GraphServiceClient;
use Psr\Http\Message\UriInterface;
use Slim\Psr7\Uri;
use TheNetworg\OAuth2\Client\Provider\Azure;
use TheNetworg\OAuth2\Client\Token\AccessToken;

final readonly class IdentityService
{
    public function __construct(
        private Azure $provider,
        private SessionInterface $session,
    ) {}

    /**
     * @param array<string, string> $options
     */
    public function getAuthorizationUrl(array $options = []): UriInterface
    {
        return UriFactory::create($this->provider->getAuthorizationUrl($options));
    }

    public function getLogoutUrl(string $redirectUri): UriInterface
    {
        return UriFactory::create($this->provider->getLogoutUrl($redirectUri));
    }

    public function getAccessToken(string $code = null): ?AccessToken
    {
        if (null === $code) {
            if (null === $token = $this->session->get('token')) {
                return null;
            }

            return unserialize($token);
        }

        $this->session->clear();

        try {
                    $token = $this->provider->getAccessToken('authorization_code', [
            'scope' => $this->provider->scope,
            'code' => $code,
        ]);
        } catch (\Throwable) {
            return null;
        }

        $this->session->set('token', serialize($token));

        return $token;
    }

        public function getIdentity(AccessToken $token = null): ?Identity
        {
            if (null === $token && null = $token = $this->getAccessToken()) {
                return null;
            }

            $resourceOwner = $this->provider->getResourceOwner($token);
            $data = $resourceOwner->toArray();

            return new Identity(
                id: $resourceOwner->getId(),
                displayName: $data['name'],
                email: $data['email'],
            );
        }

        public function clearIdentity(): void
        {
$this->session->clear();
        }
    }
