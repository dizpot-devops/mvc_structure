<?php

namespace DIZBOT\OAUTHv2\Client\Token;

use JsonSerializable;
use ReturnTypeWillChange;
use RuntimeException;

interface AccessTokenInterface extends JsonSerializable
{
    /**
     * Returns the access token string of this instance.
     *
     * @return string
     */
    public function getToken();

    /**
     * Returns the refresh token, if defined.
     *
     * @return string|null
     */
    public function getRefreshToken();

    /**
     * Returns the expiration timestamp in seconds, if defined.
     *
     * @return integer|null
     */
    public function getExpires();

    /**
     * Checks if this token has expired.
     *
     * @return boolean true if the token has expired, false otherwise.
     * @throws RuntimeException if 'expires' is not set on the token.
     */
    public function hasExpired();

    /**
     * Returns additional vendor values stored in the token.
     *
     * @return array
     */
    public function getValues();

    /**
     * Returns a string representation of the access token
     *
     * @return string
     */
    public function __toString();

    /**
     * Returns an array of parameters to serialize when this is serialized with
     * json_encode().
     *
     * @return array
     */
    #[ReturnTypeWillChange]
    public function jsonSerialize();
}
