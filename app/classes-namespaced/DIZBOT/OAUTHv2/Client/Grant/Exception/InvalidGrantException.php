<?php


namespace DIZBOT\OAUTHv2\Client\Grant\Exception;

use InvalidArgumentException;

/**
 * Exception thrown if the grant does not extend from AbstractGrant.
 *
 * @see DIZBOT\OAUTHv2\Client\Grant\AbstractGrant
 */
class InvalidGrantException extends InvalidArgumentException
{
}
