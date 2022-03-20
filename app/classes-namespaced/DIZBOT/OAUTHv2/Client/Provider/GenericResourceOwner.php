<?php


namespace DIZBOT\OAUTHv2\Client\Provider;

/**
 * Represents a generic resource owner for use with the GenericProvider.
 */
class GenericResourceOwner implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @var string
     */
    protected $resourceOwnerId;

    /**
     * @param array $response
     * @param string $resourceOwnerId
     */
    public function __construct(array $response, $resourceOwnerId)
    {
        $this->response = $response;
        $this->resourceOwnerId = $resourceOwnerId;
    }

    /**
     * Returns the identifier of the authorized resource owner.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->response[$this->resourceOwnerId];
    }

    /**
     * Returns the raw resource owner response.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
