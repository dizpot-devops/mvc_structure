<?php
class connect extends AbstractConnect {


    public function __construct($action, $request)
    {
        parent::__construct($action,$request);
        
    }

    protected function dizbot() {

        $code = isset($this->request['code']) ? $this->request['code'] : null;
        $state = isset($this->request['state']) ? $this->request['state'] : null;
        $provider = new \DIZBOT\OAUTHv2\Client\Provider\DIZBOT\DIZBOTProvider(new MySQL(),'dizbot');
        $this->setOAuthProvider($provider);
        $this->OAuthProvider->performInitialAuthorization($code,$state);
    }




}