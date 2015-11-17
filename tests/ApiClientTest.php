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
 * Api Client Test
 *
 * @author WN
 * @package Tests\ApiClient
 */
class ApiClientTest extends \PHPUnit_Framework_TestCase
{
    public function testMake()
    {
        $this->assertInstanceOf('PayBreak\ApiClient\ApiClient', ApiClient::make('http://httpbin.org/'));
    }

    public function testGet()
    {
        $api = ApiClient::make('http://httpbin.org/');

        $this->assertInternalType('array', $api->get('get'));
    }

    public function testQueryIsWorking()
    {
        $api = ApiClient::make('http://httpbin.org/');

        $response = $api->get('get', ['x' => 'y', 'z' => 'a']);

        $this->assertArrayHasKey('args', $response);

        $this->assertCount(2, $response['args']);
    }

    public function testPost()
    {
        $api = ApiClient::make('http://httpbin.org/');

        $this->assertInternalType('array', $api->post('post'));
    }

    public function testDelete()
    {
        $api = ApiClient::make('http://httpbin.org/');

        $this->assertInternalType('array', $api->delete('delete'));
    }

    public function testPut()
    {
        $api = ApiClient::make('http://httpbin.org/');

        $this->assertInternalType('array', $api->put('put'));
    }

    public function testPatch()
    {
        $api = ApiClient::make('http://httpbin.org/');

        $this->assertInternalType('array', $api->patch('patch'));
    }
}
