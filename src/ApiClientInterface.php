<?php

namespace PayBreak\ApiClient;

use Psr\Http\Message\RequestInterface;

/**
 * Interface ApiClientInterface
 *
 * @author JH
 * @package PayBreak\ApiClient
 */
interface ApiClientInterface
{
    /**
     * @author JH
     * @param RequestInterface $request
     * @param array $options
     * @return mixed
     */
    public function send(RequestInterface $request, array $options = []);

    /**
     * @author JH
     * @param $uri
     * @param array $options
     * @return mixed
     */
    public function get($uri, array $options = []);

    /**
     * @author JH
     * @param $uri
     * @param array $options
     * @return mixed
     */
    public function post($uri, array $options = []);

    /**
     * @author JH
     * @param $uri
     * @param array $options
     * @return mixed
     */
    public function put($uri, array $options = []);

    /**
     * @author JH
     * @param $uri
     * @param array $options
     * @return mixed
     */
    public function patch($uri, array $options = []);

    /**
     * @author JH
     * @param $uri
     * @param array $options
     * @return mixed
     */
    public function delete($uri, array $options = []);
}
