<?php

namespace DIZBOT\OAUTHv2\Client\Tool;

use DIZBOT\OAUTHv2\Client\Token\AccessTokenInterface;

/**
 * Enables `Bearer` header authorization for providers.
 *
 * @link http://tools.ietf.org/html/rfc6750 Bearer Token Usage (RFC 6750)
 */
trait BearerAuthorizationTrait
{
    /**
     * Returns authorization headers for the 'bearer' grant.
     *
     * @param  AccessTokenInterface|string|null $token Either a string or an access token instance
     * @return array
     */
    protected function getAuthorizationHeaders($token = null)
    {
        return ['Authorization' => 'Bearer ' . $token];
    }
}
