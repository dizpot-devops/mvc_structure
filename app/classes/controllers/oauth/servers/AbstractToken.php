<?php

abstract class AbstractToken extends OAuthV2ClientController
{
    protected $accessToken;
    protected $resourceRequestController;
    public function __construct($action,$request) {
        parent::__construct($action,$request);
       // $this->resourceRequestController = new \Oauth2\Controller\ResourceController(new Oauth2\TokenType\Bearer(),$this->storage);
       // $incomingRequest = OAuth2\Request::createFromGlobals();
        $server = $this->getServer();
        $server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
    }

}