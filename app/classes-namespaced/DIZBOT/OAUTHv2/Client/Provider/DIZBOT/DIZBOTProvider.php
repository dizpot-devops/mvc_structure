<?php

namespace DIZBOT\OAUTHv2\Client\Provider\DIZBOT;

use InvalidArgumentException;
use DIZBOT\Config\Config;
use DIZBOT\OAUTHv2\Client\Provider\Exception\IdentityProviderException;
use DIZBOT\OAUTHv2\Client\Token\AccessToken;
use DIZBOT\OAUTHv2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use DIZBOT\OAUTHv2\Client\Provider\AbstractProvider;


class DIZBOTProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected const BASE_DIZBOT_URL = 'https://dizbot.app/';
    protected const RESOURCE_PATH = '/resource/';

    private $configs;

    private $urlAuthorize;
    private $urlAccessToken;
    private $urlResourceOwnerDetails;
    private $accessTokenMethod;
    private $accessTokenResourceOwnerId;
    private $scopes = null;
    private $scopeSeparator;
    private $responseError = 'error';
    private $responseCode;
    private $responseResourceOwnerId = 'id';

    protected $api_call = '';
    protected $api_version = 'oauthv2';
    protected $fields;
    protected $sub_calls = array();


    public function __construct($storage,$call_tag,$api_call,$api_call_to='',array $sub_calls = [],array $getvars = [], array $options = [], array $collaborators = [])
    {
        //new MySQL(),$this->call_tag,$call,$slackId,$options

        $this->storage = $storage;
        $this->api_call = $api_call;
        $this->api_call_to = $api_call_to;
        $this->call_tag = $call_tag;
        $this->fields = $getvars;
        $this->sub_calls = $sub_calls;

        if(!isset($GLOBALS['environment'])) {
            $this->configs = array();
            $this->configs['dizbot'] = [
                'urlAuthorize'=>'https://dizbot.app/oauthv2/authorize/',
                'urlAccessToken'=>'https://dizbot.app/oauthv2/token/',
                'urlResourceOwnerDetails'=>'https://dizbot.app/oauthv2/resource/',
                'clientId' => 'dizgate-uxqwFr0UGcRHMwIftLxbZU-CqliRWLlzYxBzoMKp178',
                'clientSecret' => 'dizgate-w7rMbh09fNFmvNLMfpIf6lO9uO60KHGZXuCykw8f320',
                'redirectUri' => 'https://dizgate.com/connect/dizbot/',
                'refresh_uri' => 'https://dizgate.com/token/dizbot/',
                'scope'=>'global'
            ];

        }
        else {
            $this->configs = (new Config($GLOBALS['environment']))->get();
        }


        if(!empty($options['fields']) && is_array($options['fields'])) {
            $this->fields = $options['fields'];
            unset($options['fields']);
        }
        if(isset($options['version'])) {
            $this->api_version = $options['version'];
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
        //$url = $this->getDIZBOTBaseUrl() . $this->api_version . $this->getDIZBOTesourcePath() . $this->getApiCallTo();
        $url = $this->getDIZBOTBaseUrl() . $this->getDIZBOTesourcePath() . $this->api_call . "/" . $this->api_call_to . "/";

        for($i=0; $i<count($this->sub_calls); $i++) {
            $url .= $this->sub_calls[$i] . "/";
        }
        if(!empty($this->fields)) {
            $add_on = '';
            foreach($this->fields as $key=>$val) {
                if($add_on == '') { $add_on .= "/?"; }
                else { $add_on .= "&"; }
                $add_on .= $key."=".$val;
            }
            $url .= $add_on;
        }
        rtrim($url,'&');
   
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
      
        return new DIZBOTResourceOwner($response, $this->responseResourceOwnerId);
    }

    public function getDIZBOTBaseUrl() {
        return static::BASE_DIZBOT_URL;
    }
    public function getDIZBOTesourcePath() {
        return static::RESOURCE_PATH;
    }
}
