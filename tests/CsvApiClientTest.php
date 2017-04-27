<?php
/*
 * This file is part of the PayBreak\ApiClient package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Tests\ApiClient;

use PayBreak\ApiClient\CsvApiClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * Csv Api Client Test
 *
 * @author JH
 * @package Tests\ApiClient
 */
class CsvApiClientTest extends \PHPUnit_Framework_TestCase
{
    public function testMake()
    {
        $client = CsvApiClient::make('http://httpbin.org/');
        $this->assertInstanceOf('PayBreak\ApiClient\ApiClient', $client);
        $this->assertInstanceOf('PayBreak\ApiClient\CsvApiClient', $client);
    }

    public function testExceptionIsThrownWhenResponseIsNotCsv()
    {
        $client = CsvApiClient::make('http://httpbin.org/');

        $this->setExpectedException(ClientException::class);
        $client->get('get');
    }

    public function testGetCsv()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'text/csv']),
        ]);

        $client = new CsvApiClient(['handler' => HandlerStack::create($mock)]);
        $response = $client->get('http://example.com/test.csv');
        $this->assertArrayHasKey('csv', $response);
    }
}
