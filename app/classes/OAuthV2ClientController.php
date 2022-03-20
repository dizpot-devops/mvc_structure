<?php

abstract class OAuthV2ClientController
{

    protected $OAuthProvider;
    protected $accessToken;
    protected $call_tag;
    protected $request;

    public function __construct($action,$request) {
        $this->call_tag = $action;
        $this->request = $request;
    }
    public function setOAuthProvider($provider) {
        $this->OAuthProvider = $provider;
    }
	public function executeAction() {
		return $this->{$this->call_tag}();
	}


}
