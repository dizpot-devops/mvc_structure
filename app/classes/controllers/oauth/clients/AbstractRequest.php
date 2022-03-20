<?php

abstract class AbstractRequest extends OAuthV2ClientController
{
    protected $accessToken;
    public function __construct($action,$request) {
        parent::__construct($action,$request);
        
    }

}