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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RetryApiClient
 *
 * @author JH
 * @package PayBreak\ApiClient
 */
class RetryApiClient extends AbstractRetryApiClient
{
    /**
     * Determine whether the request needs to be retried or not
     *
     * @author JH
     * @return \Closure
     */
    protected function retryDecider()
    {
        return function ($retries, RequestInterface $request, ResponseInterface $response = null) {
            if ($retries >= $this->getMaxRetries()) {
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
                        static::RETRY_ATTEMPTS,
                        $response ? 'status code: ' . $response->getStatusCode() : ''
                    ));
            }

            return $shouldRetry;
        };
    }

    /**
     * @author WN
     * @param array $body
     * @return array
     */
    protected function processRequestBody(array $body)
    {
        return ['json' => $body];
    }

    /**
     * @author WN
     * @param ResponseInterface $response
     * @return array
     * @throws WrongResponseException
     */
    protected function processResponse(ResponseInterface $response)
    {
        if ($responseBody = json_decode($response->getBody()->getContents(), true)) {
            return $responseBody;
        }

        throw new WrongResponseException('Response body was malformed JSON', $response->getStatusCode());
    }

    /**
     * @author WN
     * @param ResponseInterface $response
     * @throws ErrorResponseException
     */
    protected function processErrorResponse(ResponseInterface $response)
    {
        if (($responseBody = json_decode($response->getBody()->getContents(), true)) &&
            array_key_exists('message', $responseBody)
        ) {
            throw new ErrorResponseException($responseBody['message'], $response->getStatusCode());
        }
    }
}
