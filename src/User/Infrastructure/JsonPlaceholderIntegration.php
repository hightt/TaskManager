<?php

declare(strict_types=1);

namespace App\User\Infrastructure;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JsonPlaceholderIntegration
{
    public const API_JSON_PLACEHOLDER_ENDPOINT_URL = 'https://jsonplaceholder.typicode.com/users';

    public function __construct(
        private HttpClientInterface $client
    )
    {}

    public function import()
    {
        $externalUsers = $this->client->request(Request::METHOD_GET, self::API_JSON_PLACEHOLDER_ENDPOINT_URL);

        return $externalUsers->toArray();
    }
}
