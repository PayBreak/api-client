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
 * Form Params Api Client
 *
 * @author GK
 * @package PayBreak\ApiClient
 */
class FormParamsApiClient extends ApiClient
{
    /**
     * @author GK
     * @param array $body
     * @return array
     */
    protected function processRequestBody(array $body)
    {
        return ['form_params' => $body];
    }
}
