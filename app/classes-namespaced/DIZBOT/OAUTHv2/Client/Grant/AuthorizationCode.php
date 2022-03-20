<?php


namespace DIZBOT\OAUTHv2\Client\Grant;

/**
 * Represents an authorization code grant.
 *
 * @link http://tools.ietf.org/html/rfc6749#section-1.3.1 Authorization Code (RFC 6749, §1.3.1)
 */
class AuthorizationCode extends AbstractGrant
{
    /**
     * @inheritdoc
     */
    protected function getName()
    {
        return 'authorization_code';
    }

    /**
     * @inheritdoc
     */
    protected function getRequiredRequestParameters()
    {
        return [
            'code',
        ];
    }
}
