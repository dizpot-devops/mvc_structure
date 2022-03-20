<?php


namespace DIZBOT\OAUTHv2\Client\Tool;

use DIZBOT\OAUTHv2\Client\Token\AccessToken;
use DIZBOT\OAUTHv2\Client\Token\AccessTokenInterface;

/**
 * Enables `MAC` header authorization for providers.
 *
 * @link http://tools.ietf.org/html/draft-ietf-oauth-v2-http-mac-05 Message Authentication Code (MAC) Tokens
 */
trait MacAuthorizationTrait
{
    /**
     * Returns the id of this token for MAC generation.
     *
     * @param  AccessToken $token
     * @return string
     */
    abstract protected function getTokenId(AccessToken $token);

    /**
     * Returns the MAC signature for the current request.
     *
     * @param  string $id
     * @param  integer $ts
     * @param  string $nonce
     * @return string
     */
    abstract protected function getMacSignature($id, $ts, $nonce);

    /**
     * Returns a new random string to use as the state parameter in an
     * authorization flow.
     *
     * @param  int $length Length of the random string to be generated.
     * @return string
     */
    abstract protected function getRandomState($length = 32);

    /**
     * Returns the authorization headers for the 'mac' grant.
     *
     * @param  AccessTokenInterface|string|null $token Either a string or an access token instance
     * @return array
     * @codeCoverageIgnore
     *
     * @todo This is currently untested and provided only as an example. If you
     * complete the implementation, please create a pull request for
     * https://github.com/thephpleague/oauth2-client
     */
    protected function getAuthorizationHeaders($token = null)
    {
        if ($token === null) {
            return [];
        }

        $ts    = time();
        $id    = $this->getTokenId($token);
        $nonce = $this->getRandomState(16);
        $mac   = $this->getMacSignature($id, $ts, $nonce);

        $parts = [];
        foreach (compact('id', 'ts', 'nonce', 'mac') as $key => $value) {
            $parts[] = sprintf('%s="%s"', $key, $value);
        }

        return ['Authorization' => 'MAC ' . implode(', ', $parts)];
    }
}
