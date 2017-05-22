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
 * Csv Api Client
 *
 * @author JH
 * @package PayBreak\ApiClient
 */
class CsvApiClient extends ApiClient
{
    /**
     * @author JH
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

        if (strpos($response->getHeaderLine('Content-Type'), 'csv') !== false) {
            return ['csv' => $response->getBody()->getContents()] ;
        }

        throw new WrongResponseException('Response body was malformed csv', $response->getStatusCode());
    }

    /**
     * @author EA
     * @param string $uri
     * @param array $query
     * @param array $headers
     * @return array
     * @throws ErrorResponseException
     * @throws \Exception
     */
    public function get($uri, array $query = [], array $headers = [])
    {
        if (substr($uri, -4) !== '.csv') {
            $uri .= '.csv';
        }

        return parent::get($uri, $query, $headers);
    }
}
