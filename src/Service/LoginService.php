<?php

declare(strict_types=1);

namespace H22k\MngKargo\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use H22k\MngKargo\Contract\ClientInterface;
use JsonException;

/**
 * This class is responsible for login to MNG API service.
 */
class LoginService
{
    private const LOGIN_URI = 'token';

    public function __construct(
        private readonly string $mngClientNumber,
        private readonly string $mngPassword,
    ) {
    }

    /**
     * Get JWT token from MNG API service to use other services.
     *
     * @throws GuzzleException
     * @throws JsonException
     */
    public function login(Client|ClientInterface $client, string $apiKey, string $apiSecret): string
    {
        $response = $client->post(
            self::LOGIN_URI,
            [
                'json' => [ // request body
                    'customerNumber' => $this->mngClientNumber,
                    'password' => $this->mngPassword,
                    'identityType' => 1
                ],
                'headers' => [ // request header
                    'X-IBM-Client-Id' => $apiKey,
                    'X-IBM-Client-Secret' => $apiSecret,
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }
}
