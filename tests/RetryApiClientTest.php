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

use GuzzleHttp\Handler\MockHandler;
use PayBreak\ApiClient\RetryApiClient;
use PayBreak\ApiClient\Test\TestLogger;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LogLevel;

/**
 * Api Client Test
 *
 * @author JH
 * @package Tests\ApiClient
 */
class RetryApiClientTest extends \PHPUnit_Framework_TestCase
{
    public function testMake()
    {
        $client = RetryApiClient::make('http://httpbin.org/');
        $this->assertInstanceOf('PayBreak\ApiClient\ApiClient', $client);
        $this->assertInstanceOf('PayBreak\ApiClient\RetryApiClient', $client);
    }

    /**
     * Test that RetryGuzzleAdaptor retries the appropriate number of times
     * When there is an error
     *
     * @author JH
     */
    public function testRetryOnFailedRequest()
    {
        $logger = new TestLogger();
        $client = new RetryApiClient([], $logger);

        try {
            $client->post('http://httpbin.org/status/500', ['test' => 123]);
        } catch (\Exception $e) {
            // Exception is expected
        }

        // Filter log to only show retry messages
        $attempts = array_filter($logger->getLog(), function ($attempt) {
            return $attempt['level'] === LogLevel::NOTICE;
        });

        $this->assertEquals(RetryApiClient::RETRY_ATTEMPTS, count($attempts));
    }

    /**
     * Test that RetryGuzzleAdaptor does not retry when the response is successful
     * @author JH
     */
    public function testDoesNotRetryOnSuccessfulRequest()
    {
        $logger = new TestLogger();
        $client = new RetryApiClient([], $logger);
        $client->get('http://httpbin.org/get');

        $this->assertEmpty($logger->getLog());
    }
}
