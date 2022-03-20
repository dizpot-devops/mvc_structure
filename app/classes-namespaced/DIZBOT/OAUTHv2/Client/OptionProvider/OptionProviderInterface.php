<?php


namespace DIZBOT\OAUTHv2\Client\OptionProvider;

/**
 * Interface for access token options provider
 */
interface OptionProviderInterface
{
    /**
     * Builds request options used for requesting an access token.
     *
     * @param string $method
     * @param  array $params
     * @return array
     */
    public function getAccessTokenOptions($method, array $params);
}
