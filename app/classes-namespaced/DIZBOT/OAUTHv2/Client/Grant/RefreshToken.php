<?php


namespace DIZBOT\OAUTHv2\Client\Grant;

/**
 * Represents a refresh token grant.
 *
 * @link http://tools.ietf.org/html/rfc6749#section-6 Refreshing an Access Token (RFC 6749, §6)
 */
class RefreshToken extends AbstractGrant
{
    /**
     * @inheritdoc
     */
    protected function getName()
    {
        return 'refresh_token';
    }

    /**
     * @inheritdoc
     */
    protected function getRequiredRequestParameters()
    {
        return [
            'refresh_token',
        ];
    }
}
