<?php
class resource extends AbstractResource {


    

    public function __construct($action, $api_call,$sub_calls,$request)
    {
        parent::__construct($action, $api_call,$sub_calls,$request);
    }
    protected function slack() {
        $response = new OAuth2\Response();
        $handler = new SLACK_handler();

        if($this->api_call == null) {

            $response->setError(400,'MORE INFO REQUIRED','No API call provided');  //setStatusCode('400','No recipient Slack ID provided to send DM to');
            $response->send();
            return $response;

        }
        
        $result = $handler->{$this->api_call}($this->sub_calls,$this->getvars);
        $response->addParameters($result);
        $response->send();
        exit;
    }




    protected function tradegecko($api_call = null, $sub_calls = array(), $options = array()) {

        $handler = new TRADEGECKO_handler();
        if($api_call == null) {
            $api_call = $this->api_call;
            $sub_calls = $this->sub_calls;
            $getvars = $this->getvars;
        }
        if($api_call == null) {
            echo "BAD FORM"; exit;
        }
        $results = $handler->{$api_call}($sub_calls,$getvars);
        var_dump($results);
    
    }


}
