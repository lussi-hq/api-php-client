<?php

namespace Lussi\Service\Verify;

use Lussi\Client;
use Lussi\Enums\Channels;
use Lussi\Service\AbstractAuthenticatedService;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Component\HttpClient\Exception\ClientException;

/**
 * The Lussi Verify API Service
 * https://lussi.pro/products/verify/getting-started
 */
class Verify extends AbstractAuthenticatedService
{
    private string $serviceId;
    
    /**
     * Construct The Lussi SMS API Service
     */
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    public function services(string $serviceId): self
    {
        $this->serviceId = $serviceId;
        return $this;
    }

    /**
     * Request a verification code
     */
    public function createVerification(string $to, string $channel, ?string $region = null): ResponseInterface
    {
        $url = $this->client->getHost() . '/verify/v1/services/' . $this->serviceId;
        $headers = [
            'Authorization' =>  'Bearer ' . $this->getAccessToken(),
            'Content-Type'  =>  'application/json'
        ];
        $data = json_encode([
            'to' => $to,
            'region' => $region,
            'channel' => $channel
        ]);

        return $this->client->getHttpClient()->request('POST', $url, [
            'headers' => $headers,
            'body' => $data
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new ClientException($response);
        }

        return $response;
    }

    /**
     * Verify a verification code
     */
    public function checkVerification(string $to, string $code, string $channel, ?string $region = null): ResponseInterface
    {
        $url = $this->client->getHost() . '/verify/v1/services/' . $this->serviceId . '/check';
        $headers = [
            'Authorization' =>  'Bearer ' . $this->getAccessToken(),
            'Content-Type'  =>  'application/json'
        ];
        $data = json_encode([
            'to' => $to,
            'code' => $code,
            'region' => $region,
            'channel' => $channel
        ]);

        return $this->client->getHttpClient()->request('POST', $url, [
            'headers' => $headers,
            'body' => $data
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new ClientException($response);
        }

        return $response;
    }
}