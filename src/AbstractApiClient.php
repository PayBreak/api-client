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
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception;
use GuzzleHttp\Exception\GuzzleException;
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

    /** @var ClientInterface */
    private $client;
    private $logger;
    private $headers;

    /**
     * @author WN
     * @param array $config
     * @param LoggerInterface $logger
     * @param array $headers
     * @throws \Exception
     */
    public function __construct(array $config = [], LoggerInterface $logger = null, array $headers = [])
    {
        $this->client = $this->initialiseClient($config);

        if (!$this->client instanceof ClientInterface) {
            throw new \Exception('Implementation of AbstractApiClient must implement '. ClientInterface::class);
        }

        $this->logger = $logger;
        $this->headers = $headers;
    }

    /**
     * @author EB
     * @return ClientInterface
     */
    protected function getClient()
    {
        return $this->client;
    }

    /**
     * @author EB
     * @param ClientInterface $client
     * @return ClientInterface
     */
    protected function setClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this->client;
    }

    /**
     * @author JH
     * @param array $config
     * @return \GuzzleHttp\ClientInterface
     * @throws \Exception
     */
    protected function initialiseClient(array $config = [])
    {
        return $this->setClient(new Client($config));
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
     * @param array $headers
     * @return array
     * @throws ErrorResponseException
     * @throws \Exception
     */
    public function get($uri, array $query = [], array $headers = [])
    {
        return $this->send((new Request('GET', $uri)), [], $query, $headers);
    }

    /**
     * @author WN
     * @param $uri
     * @param array $body
     * @param array $query
     * @param array $headers
     * @return array
     * @throws ErrorResponseException
     * @throws \Exception
     */
    public function post($uri, array $body = [], array $query = [], array $headers = [])
    {
        return $this->send((new Request('POST', $uri)), $body, $query, $headers);
    }

    /**
     * @author WN
     * @param $uri
     * @param array $body
     * @param array $query
     * @param array $headers
     * @return array
     * @throws ErrorResponseException
     * @throws \Exception
     */
    public function put($uri, array $body = [], array $query = [], array $headers = [])
    {
        return $this->send((new Request('PUT', $uri)), $body, $query, $headers);
    }

    /**
     * @author WN
     * @param $uri
     * @param array $body
     * @param array $query
     * @param array $headers
     * @return array
     * @throws ErrorResponseException
     * @throws \Exception
     */
    public function patch($uri, array $body = [], array $query = [], array $headers = [])
    {
        return $this->send((new Request('PATCH', $uri)), $body, $query, $headers);
    }

    /**
     * @author WN
     * @param $uri
     * @param array $query
     * @param array $headers
     * @return array
     * @throws ErrorResponseException
     * @throws \Exception
     */
    public function delete($uri, array $query = [], array $headers = [])
    {
        return $this->send((new Request('DELETE', $uri)), [], $query, $headers);
    }

    /**
     * @author WN
     * @param RequestInterface $request
     * @param array $body
     * @param array $query
     * @param array $headers
     * @return array
     * @throws ErrorResponseException
     * @throws \Exception
     */
    private function send(RequestInterface $request, array $body = [], array $query = [], array $headers = [])
    {
        $options = $this->processRequestBody($body);
        $this->processQuery($query, $options);
        $this->processHeaders($headers, $options);

        try {
            $response = $this->getClient()->send($request, $options);

            return $this->processResponse($response, $request);
        } catch (GuzzleException $e) {
            $this->handleException($e, $request);
        }
    }

    /**
     * @author EB
     * @param GuzzleException $e
     * @param RequestInterface $request
     */
    protected function handleException(GuzzleException $e, RequestInterface $request)
    {
        if ($e instanceof Exception\ClientException) {
            $this->processErrorResponse($e->getResponse(), $request);
        } elseif ($e instanceof Exception\BadResponseException) {
            $this->logError(
                'Api Bad Response from [' . $request->getUri() . '] Failed[' . $e->getResponse()->getStatusCode() . ']',
                $this->formatBadResponseException($e)
            );
        } elseif ($e instanceof Exception\RequestException) {
            $this->logError(
                'Api problem with request to [' . $request->getUri() . ']',
                $this->formatRequestException($e)
            );
        }

        throw $e;
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
     * @param array $headers
     * @param array $options
     */
    private function processHeaders(array $headers, array &$options)
    {
        $headers = array_merge($this->headers, $headers);

        $headers['Content-Length'] = 0; // Issue https://github.com/guzzle/guzzle/issues/1645

        if (count($headers) > 0) {
            $options['headers'] = $headers;
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
     * @param RequestInterface $request
     * @return array
     * @throws WrongResponseException
     */
    abstract protected function processResponse(ResponseInterface $response, RequestInterface $request);

    /**
     * @author WN
     * @param ResponseInterface $response
     * @param RequestInterface $request
     * @throws ErrorResponseException
     */
    abstract protected function processErrorResponse(ResponseInterface $response, RequestInterface $request);
}
