<?php

class DIZGATE_handler
{
    private $call_tag = 'dizgate';
    private $accessToken;

    public function sop($call,$options = array()) {
        $provider_class = "DIZBOT\\OAUTHv2\\Client\\Provider\\DIZGATE\\DIZGATEProvider";
        
        $provider = new $provider_class(new MySQL(),'sop',$call,$options);
        
        $this->accessToken = $provider->getOrRefreshAccessToken();
        $resource_owner = $provider->getResourceOwner($this->accessToken);
        return $resource_owner->getAllResults();
    }


    public function employees($call,$options = array()) {
        $provider_class = "DIZBOT\\OAUTHv2\\Client\\Provider\\DIZGATE\\DIZGATEProvider";
        $provider = new $provider_class(new MySQL(),'employees',$call,$options);
        $this->accessToken = $provider->getOrRefreshAccessToken();
        $resource_owner = $provider->getResourceOwner($this->accessToken);
        return $resource_owner->getAllResults();
    }

}

//curl https://slack.com/api/conversations.list -H "Authorization: Bearer xoxb-1234..."

