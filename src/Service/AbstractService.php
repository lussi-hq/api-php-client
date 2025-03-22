<?php

namespace Lussi\Service;

use Lussi\Client;


/**
 * An abstract class extended by all services
 */
abstract class AbstractService
{
    /**
     * @var Client The Lussi API Client
     */
    protected $client;

    /**
     * Construct The Lussi Service
     * 
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
