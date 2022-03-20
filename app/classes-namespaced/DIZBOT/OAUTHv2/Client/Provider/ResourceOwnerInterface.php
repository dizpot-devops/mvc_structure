<?php
namespace DIZBOT\OAUTHv2\Client\Provider;

/**
 * Classes implementing `ResourceOwnerInterface` may be used to represent
 * the resource owner authenticated with a service provider.
 */
interface ResourceOwnerInterface
{
    /**
     * Returns the identifier of the authorized resource owner.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray();
}
