<?php

abstract class AbstractResource extends OAuthV2ServerController
{
    protected $accessToken;
    protected $resourceRequestController;
    public function __construct($action,$api_call,$sub_calls,$request) {
        parent::__construct($action,$api_call,$sub_calls,$request);

        // Authenticate Resource Call with OAuthV2
        $server = $this->getServer();
        $request = OAuth2\Request::createFromGlobals();

        // if (! $server->verifyResourceRequest($request)) {
        //     $server->getResponse()->send();
        //     die;
        // }

    }


}