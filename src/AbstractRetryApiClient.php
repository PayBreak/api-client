<?php
/*
 * This file is part of the PayBreak\ApiClient package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PayBreak\ApiClient;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

/**
 * Class AbstractRetryApiClient
 *
 * @author JH
 * @package PayBreak\ApiClient
 */
abstract class AbstractRetryApiClient extends AbstractApiClient
{
    /**
     * Initialise a Guzzle Client which uses the retry middleware
     *
     * @author JH
     * @param array $config
     * @return Client
     */
    protected function initialiseClient(array $config = [])
    {
        if (!isset($config['handler'])) {
            $handlerStack = HandlerStack::create(new CurlHandler());
            $handlerStack->push(
                Middleware::retry(
                    $this->retryDecider()
                )
            );

            $config['handler'] = $handlerStack;
        }

        return new Client($config);
    }

    /**
     * Define the conditions for retrying the request
     *
     * @author JH
     * @return \Closure
     */
    abstract protected function retryDecider();

    /**
     * The number of times to retry an unsuccessful request
     *
     * @author JH
     * @return int
     */
    abstract protected function getMaxRetries();
}
