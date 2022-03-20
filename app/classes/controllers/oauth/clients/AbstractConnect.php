<?php

abstract class AbstractConnect extends OAuthV2ClientController
{

    public function __construct($action,$request) {
        parent::__construct($action,$request);
    }
}