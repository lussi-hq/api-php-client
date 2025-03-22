<?php

namespace Lussi\Authentication;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\Exception\ClientException;


/**
 * Lussi Token Authentication
 */
class TokenAuthentication
{
	/**
     * Token life time in seconds
     * 
     * @var const
     */
	public const TOKEN_LIFE_TIME = 3600;

    /**
     * @var string
     */
    protected $host;

    /**
     * @param string $host
     */
    public function __construct(string $host)
    {
        $this->host = $host;
    }
    
	/**
     * Fetch a fresh Access Token
     * 
     * @param HttpClientInterface $client
	 * @param string $authorizationHeader
	 * 
	 * @throws ClientException Get Access Token Failled
	 * 
	 * @return string
	 */
    public function makeTokenRequest(HttpClientInterface $client, array $credentials): string
    {
        $response = $client->request('POST', $this->getEndpoint(), [
            'headers'   =>  [
                'Content-Type' => 'application/json',
				'Accept'        =>  'application/json'
			],
            'body'      =>  json_encode($credentials),
          ]);

        if (200 !== $response->getStatusCode()) {
            throw new ClientException($response);
        }
        
		return $response->toArray()['access_token'];
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->host . '/oauth/v1/token';
    }
}
