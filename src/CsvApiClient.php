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

use Psr\Http\Message\ResponseInterface;

/**
 * Class CsvApiClient
 * @author JH
 * @package PayBreak\ApiClient
 */
class CsvApiClient extends ApiClient
{
    /**
     * @author JH
     * @param ResponseInterface $response
     * @return array
     * @throws WrongResponseException
     */
    protected function processResponse(ResponseInterface $response)
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
        return parent::get($uri . '.csv', $query, $headers);
    }
}
