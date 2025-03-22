<?php

namespace Lussi;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpClient\HttpClient;
use Lussi\Authentication\TokenAuthentication;
use Lussi\Service\Verify\Verify;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * The Lussi API Client
 * https://github.com/lussi-hq/api-php-client
 */
class Client
{
    /**
     * @var string $host The Lussi API URL
     */
    public $host;

    /**
     * @var CacheInterface $cache
     */
    protected $cache;

    /**
     * @var HttpClientInterface $httpClient
     */
    protected $httpClient;

    /**
     * @var string $clientId The client Identifier
     */
    protected $clientId;

    /**
     * @var string $clientSecret The client secret
     */
    protected $clientSecret;

    /**
     * @var Verify $verify The Verify Service
     */
    public $verify;

    /**
     * Construct the Lussi Cient
     * 
     * @param CaccheInerface $cache
     * @param string $clientId
     * @param string $clientSecret
     * @param string $host
     */
    public function __construct(CacheInterface $cache, string $clientId = '', string $clientSecret = '', string $host = 'https://api.lussi.pro')
    {
        $this->cache = $cache;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->host = $host;
        $this->httpClient = HttpClient::create();

        $this->verify = new Verify($this);
    }

    /**
     * Retrieve Access Token from cache or request a new Access Token
     * 
     * @return string access token
     */
    public function getAccessToken(): string
    {
        return $this->cache->get('lussi-api-client-access-token', function(ItemInterface $item) {
            $tokenAuthentication = new TokenAuthentication($this->host);
            $token = $tokenAuthentication->makeTokenRequest($this->httpClient, $this->getCredentials());
            $item->expiresAfter((int)TokenAuthentication::TOKEN_LIFE_TIME);

            return $token;
        });
    }

    /**
     * @return array Credentials
     */
    public function getCredentials(): array
    {
        return [
            'username' => $this->clientId,
            'password' => $this->clientSecret,
        ];
    }

    /**
     * @return string|null
     */
    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    /**
     * @var string $clientId
     * @return self
     */
    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    /**
     * @var string $clientSecret
     * @return self
     */
    public function setClientSecret(string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @var string $host
     * @return self
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }
}
