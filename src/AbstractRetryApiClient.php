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
    // Number of times to retry.
    // This can be overridden on the child class
    const RETRY_ATTEMPTS = 3;

    /**
     * Set up retry middleware
     *
     * @author JH
     * @param array $config
     */
    protected function configure(array &$config = [])
    {
        if (!isset($config['handler']) || !$config['handler'] instanceof HandlerStack) {
            $handlerStack = HandlerStack::create(new CurlHandler());
            $handlerStack->push(
                Middleware::retry(
                    $this->retryDecider()
                )
            );

            $config['handler'] = $handlerStack;
        }
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
    protected function getMaxRetries()
    {
        return static::RETRY_ATTEMPTS;
    }
}
