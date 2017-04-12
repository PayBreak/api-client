<?php

namespace PayBreak\ApiClient;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;

/**
 * Class GuzzleWrapper
 *
 * @author JH
 * @package PayBreak\ApiClient
 */
class GuzzleWrapper implements ApiClientInterface
{
    protected $client;

    public function __construct(array $config = [])
    {
        $this->client = new Client($config);
    }

    /**
     * @author JH
     * @param RequestInterface $request
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(RequestInterface $request, array $options = [])
    {
        return $this->client->send($request, $options);
    }

    /**
     * @author JH
     * @param $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get($uri, array $options = [])
    {
        return $this->client->get($uri, $options);
    }

    /**
     * @author JH
     * @param $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function post($uri, array $options = [])
    {
        return $this->client->post($uri, $options);
    }

    /**
     * @author JH
     * @param $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function put($uri, array $options = [])
    {
        return $this->client->put($uri, $options);
    }

    /**
     * @author JH
     * @param $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function patch($uri, array $options = [])
    {
        return $this->client->patch($uri, $options);
    }

    /**
     * @author JH
     * @param $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete($uri, array $options = [])
    {
        return $this->client->delete($uri, $options);
    }
}
