<?php


namespace DIZBOT\OAUTHv2\Client\Tool;

use BadMethodCallException;

/**
 * Provides functionality to check for required parameters.
 */
trait RequiredParameterTrait
{
    /**
     * Checks for a required parameter in a hash.
     *
     * @throws BadMethodCallException
     * @param  string $name
     * @param  array  $params
     * @return void
     */
    private function checkRequiredParameter($name, array $params)
    {
        if (!isset($params[$name])) {
            throw new BadMethodCallException(sprintf(
                'Required parameter not passed: "%s"',
                $name
            ));
        }
    }

    /**
     * Checks for multiple required parameters in a hash.
     *
     * @throws InvalidArgumentException
     * @param  array $names
     * @param  array $params
     * @return void
     */
    private function checkRequiredParameters(array $names, array $params)
    {
        foreach ($names as $name) {
            $this->checkRequiredParameter($name, $params);
        }
    }
}
