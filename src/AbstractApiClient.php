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
use GuzzleHttp\Exception;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use PayBreak\Foundation\Logger\PsrLoggerTrait;

/**
 * Abstract Api Client
 *
 * @author WN
 * @package PayBreak\ApiClient
 */
abstract class AbstractApiClient
{
    use PsrLoggerTrait;

    private $client;
    private $logger;

    /**
     * @author WN
     * @param array $config
     * @param LoggerInterface $logger
     */
    public function __construct(array $config = [], LoggerInterface $logger = null)
    {
        $this->client = new Client($config);
        $this->logger = $logger;
    }

    /**
     * @author WN
     * @param $baseUrl
     * @return static
     */
    public static function make($baseUrl)
    {
        return new static(
            [
                'base_uri' => $baseUrl
            ]
        );
    }

    /**
     * @author WN
     * @param $uri
     * @param array $query
     * @return array
     * @throws ErrorResponseException
     * @throws \Exception
     */
    public function get($uri, array $query = [])
    {
        return $this->send((new Request('GET', $uri)), [], $query);
    }

    /**
     * @author WN
     * @param $uri
     * @param array $body
     * @param array $query
     * @return array
     * @throws ErrorResponseException
     * @throws \Exception
     */
    public function post($uri, array $body = [], array $query = [])
    {
        return $this->send((new Request('POST', $uri)), $body, $query);
    }

    /**
     * @author WN
     * @param $uri
     * @param array $body
     * @param array $query
     * @return array
     * @throws ErrorResponseException
     * @throws \Exception
     */
    public function put($uri, array $body = [], array $query = [])
    {
        return $this->send((new Request('PUT', $uri)), $body, $query);
    }

    /**
     * @author WN
     * @param $uri
     * @param array $body
     * @param array $query
     * @return array
     * @throws ErrorResponseException
     * @throws \Exception
     */
    public function patch($uri, array $body = [], array $query = [])
    {
        return $this->send((new Request('PATCH', $uri)), $body, $query);
    }

    /**
     * @author WN
     * @param $uri
     * @param array $query
     * @return array
     * @throws ErrorResponseException
     * @throws \Exception
     */
    public function delete($uri, array $query = [])
    {
        return $this->send((new Request('DELETE', $uri)), [], $query);
    }

    /**
     * @author WN
     * @param RequestInterface $request
     * @param array $body
     * @param array $query
     * @return array
     * @throws ErrorResponseException
     * @throws \Exception
     */
    private function send(RequestInterface $request, array $body = [], array $query = [])
    {
        $options = $this->processRequestBody($body);
        $this->processQuery($query, $options);

        try {
            $response = $this->client->send($request, $options);
            return $this->processResponse($response);

        } catch (Exception\ClientException $e) {

            $this->processErrorResponse($e->getResponse());
            throw $e;

        } catch (Exception\BadResponseException $e) {

            $this->logError(
                'Api Bad Response from [' . $request->getUri() . '] Failed[' . $e->getResponse()->getStatusCode() . ']',
                $this->formatBadResponseException($e)
            );
            throw $e;

        } catch (Exception\RequestException $e) {

            $this->logError(
                'Api problem with request to [' . $request->getUri() . ']',
                $this->formatRequestException($e)
            );
            throw $e;
        }
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * @author WN
     * @param array $query
     * @param array $options
     */
    private function processQuery(array $query, array &$options)
    {
        if (count($query) > 0) {
            $options['query'] = $query;
        }
    }

    /**
     * @author WN
     * @param Exception\BadResponseException $e
     * @return array
     */
    private function formatBadResponseException(Exception\BadResponseException $e)
    {
        return [
            'message' => $e->getMessage(),
            'request' => [
                'headers'   => $e->getRequest()->getHeaders(),
                'body'      => $e->getRequest()->getBody()->getContents(),
                'method'    => $e->getRequest()->getMethod(),
                'uri'       => $e->getRequest()->getUri(),
            ],
            'response' => [
                'body'      => ($e->getResponse())?$e->getResponse()->getBody()->getContents():'[EMPTY]',
                'headers'   => ($e->getResponse())?$e->getResponse()->getHeaders():'[EMPTY]',
            ],
        ];
    }

    /**
     * @author WN
     * @param Exception\RequestException $e
     * @return array
     */
    private function formatRequestException(Exception\RequestException $e)
    {
        return [
            'message' => $e->getMessage(),
            'request' => [
                'headers'   => $e->getRequest()->getHeaders(),
                'body'      => $e->getRequest()->getBody()->getContents(),
                'method'    => $e->getRequest()->getMethod(),
                'uri'       => $e->getRequest()->getUri(),
            ],
        ];
    }

    /**
     * @author WN
     * @param array $body
     * @return array
     */
    abstract protected function processRequestBody(array $body);

    /**
     * @author WN
     * @param ResponseInterface $response
     * @return array
     * @throws WrongResponseException
     */
    abstract protected function processResponse(ResponseInterface $response);

    /**
     * @author WN
     * @param ResponseInterface $response
     * @throws ErrorResponseException
     */
    abstract protected function processErrorResponse(ResponseInterface $response);
}
