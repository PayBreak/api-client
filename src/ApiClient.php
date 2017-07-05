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
 * Api Client
 *
 * @author WN
 * @package PayBreak\ApiClient
 */
class ApiClient extends AbstractApiClient
{
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
     * @param RequestInterface $request
     * @return array
     * @throws WrongResponseException
     */
    protected function processResponse(ResponseInterface $response, RequestInterface $request)
    {
        if ($response->getStatusCode() == 204) {
            return [];
        }

        $responseBody = json_decode($response->getBody()->getContents(), true);

        if (is_array($responseBody)) {
            return $responseBody;
        }

        throw new WrongResponseException('Response body was malformed JSON', $response->getStatusCode());
    }

    /**
     * @author WN
     * @param ResponseInterface $response
     * @param RequestInterface $request
     * @throws ErrorResponseException
     */
    protected function processErrorResponse(ResponseInterface $response, RequestInterface $request)
    {
        if ($responseBody = json_decode($response->getBody()->getContents(), true)) {
            if (array_key_exists('message', $responseBody)) {
                throw new ErrorResponseException($responseBody['message'], $response->getStatusCode());
            }

            if (array_key_exists('error', $responseBody)) {
                throw new ErrorResponseException($responseBody['error'], $response->getStatusCode());
            }
        }
    }
}
