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

use PayBreak\ApiClient\ApiClient;

/**
 * Abstract Api Client Test
 *
 * @author WN
 * @package Tests\ApiClient
 */
class AbstractApiClientTest extends \PHPUnit_Framework_TestCase
{
    public function testClientException()
    {
        $api = ApiClient::make('http://httpbin.org/status/418');

        $this->setExpectedException('GuzzleHttp\Exception\ClientException');

        $api->get('');
    }

    public function testWrongResponse()
    {
        $api = ApiClient::make('http://httpbin.org/xml');

        $this->setExpectedException('PayBreak\ApiClient\WrongResponseException');

        $api->get('');
    }

    public function testBadRequest()
    {
        $api = ApiClient::make('htctp://httpbin.org/xml');

        $this->setExpectedException('GuzzleHttp\Exception\RequestException');

        $api->get('');
    }

    public function testBadResponse()
    {
        $api = ApiClient::make('http://httpbin.org/status/500');

        $this->setExpectedException('GuzzleHttp\Exception\BadResponseException');

        $api->get('');
    }
}
