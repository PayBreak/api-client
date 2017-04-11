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
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class RetryApiClient
 *
 * @author JH
 * @package PayBreak\ApiClient
 */
class RetryApiClient extends ApiClient
{
    const RETRY_ATTEMPTS = 3;

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
     * Determine whether the request needs to be retried or not
     *
     * @author JH
     * @return \Closure
     */
    protected function retryDecider()
    {
        return function ($retries, RequestInterface $request, ResponseInterface $response = null) {
            if ($retries >= self::RETRY_ATTEMPTS) {
                return false;
            }

            $shouldRetry = false;
            // Retry on server errors
            if (isset($response) && $response->getStatusCode() >= 500) {
                $shouldRetry = true;
            }

            if ($shouldRetry) {
                $this->logNotice(
                    sprintf(
                        'Retrying %s %s %s/%s, %s',
                        $request->getMethod(),
                        $request->getUri(),
                        $retries + 1,
                        self::RETRY_ATTEMPTS,
                        $response ? 'status code: ' . $response->getStatusCode() : ''
                    ));
            }

            return $shouldRetry;
        };
    }
}
