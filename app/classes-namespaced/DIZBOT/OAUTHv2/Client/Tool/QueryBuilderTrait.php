<?php

namespace DIZBOT\OAUTHv2\Client\Tool;

/**
 * Provides a standard way to generate query strings.
 */
trait QueryBuilderTrait
{
    /**
     * Build a query string from an array.
     *
     * @param array $params
     *
     * @return string
     */
    protected function buildQueryString(array $params)
    {
        return http_build_query($params, '', '&', \PHP_QUERY_RFC3986);
    }
}
