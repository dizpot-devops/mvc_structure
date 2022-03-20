<?php

namespace DIZBOT\OAUTHv2\Client\Provider;

use InvalidArgumentException;
use DIZBOT\Config\Config;
use DIZBOT\OAUTHv2\Client\Provider\Exception\IdentityProviderException;
use DIZBOT\OAUTHv2\Client\Token\AccessToken;
use DIZBOT\OAUTHv2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use DIZBOT\OAUTHv2\Client\Provider\AbstractProvider;


class TEMPLATEProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;


    private $configs;

    protected $urlAuthorize;
    protected $urlAccessToken;
    protected $urlResourceOwnerDetails;
    protected $accessTokenMethod;
    protected $accessTokenResourceOwnerId;
    protected $scopes = null;
    protected $scopeSeparator;
    protected $responseError = 'error';
    protected $responseCode;
    protected $responseResourceOwnerId = 'id';

    protected $api_type = '';
    protected $api_call = '';
    protected $fields;


    public function __construct($storage,$call_tag,$getvars = [],array $options = [], array $collaborators = [])
    {

        $this->storage = $storage;
        $this->call_tag = $call_tag;
        $this->fields = $getvars;
        $this->configs = (new Config($GLOBALS['environment']))->get();

        if(!empty($options['fields']) && is_array($options['fields'])) {
            $this->fields = $options['fields'];
            unset($options['fields']);
        }

         if(empty($this->configs)) {
             throw new InvalidArgumentException(
                 'configs not loaded, check the call_tag and make sure configs exist'
             );
         }
        $options = $this->configs[$this->call_tag];

        $this->assertRequiredOptions($options);

        $possible   = $this->getConfigurableOptions();
        $configured = array_intersect_key($options, array_flip($possible));

        foreach ($configured as $key => $value) {
            $this->$key = $value;
        }

        // Remove all options that are only used locally
        $options = array_diff_key($options, $configured);

        parent::__construct($options, $collaborators);

    }
    public function setApiCallTo($call_to) {
        $this->api_call = $call_to;
    }
    public function getApiCallTo() {
        return $this->api_call;
    }
    public function getApiType() {
        return $this->api_type;
    }

    /**
     * Returns all options that can be configured.
     */
    protected function getConfigurableOptions()
    {
        return array_merge($this->getRequiredOptions(), [
            'accessTokenMethod',
            'accessTokenResourceOwnerId',
            'scopeSeparator',
            'responseError',
            'responseCode',
            'responseResourceOwnerId',
            'scopes',
        ]);
    }

    /**
     * Returns all options that are required.
     */
    protected function getRequiredOptions()
    {
        return [
            'urlAuthorize',
            'urlAccessToken',
            'urlResourceOwnerDetails',
        ];
    }

    /**
     * Verifies that all required options have been passed.
     */
    private function assertRequiredOptions(array $options)
    {
        $missing = array_diff_key(array_flip($this->getRequiredOptions()), $options);

        if (!empty($missing)) {
            throw new InvalidArgumentException(
                'Required options not defined: ' . implode(', ', array_keys($missing))
            );
        }
    }


    public function getBaseAuthorizationUrl()
    {
        return $this->urlAuthorize;
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->urlAccessToken;
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        $url = $this->urlResourceOwnerDetails;
        if(!empty($this->fields)) {
            $url .= "/?";
            foreach($this->fields as $key=>$val) {
                $url .= $key."=".$val."&";
            }
            rtrim($url,'&');
        }
        return $url;
    }

    public function getDefaultScopes()
    {
        return $this->scopes;
    }

    protected function getAccessTokenMethod()
    {
        return $this->accessTokenMethod ?: parent::getAccessTokenMethod();
    }

    protected function getAccessTokenResourceOwnerId()
    {
        return $this->accessTokenResourceOwnerId ?: parent::getAccessTokenResourceOwnerId();
    }

    protected function getScopeSeparator()
    {
        return $this->scopeSeparator ?: parent::getScopeSeparator();
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!empty($data[$this->responseError])) {
            $error = $data[$this->responseError];
            if (!is_string($error)) {
                $error = var_export($error, true);
            }
            $code  = $this->responseCode && !empty($data[$this->responseCode])? $data[$this->responseCode] : 0;
            if (!is_int($code)) {
                $code = intval($code);
            }
            throw new IdentityProviderException($error, $code, $data);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new GenericResourceOwner($response, $this->responseResourceOwnerId);
    }
}
