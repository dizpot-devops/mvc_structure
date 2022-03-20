<?php

namespace DIZBOT\OAUTHv2\Client\Token;

interface ResourceOwnerAccessTokenInterface extends AccessTokenInterface
{
    /**
     * Returns the resource owner identifier, if defined.
     *
     * @return string|null
     */
    public function getResourceOwnerId();
}
