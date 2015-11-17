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

/**
 * Bad Response Exception
 *
 * Response was received from server but message is not readable.
 *
 * @author WN
 * @package PayBreak\ApiClient
 */
class WrongResponseException extends ApiClientException
{
}
