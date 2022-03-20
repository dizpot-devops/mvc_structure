<?php
class request extends AbstractRequest {


    public function __construct($action, $request)
    {
        parent::__construct($action,$request);
    }


    protected function dizgate($method = null,$call = null, $options = array()) {
        if($method == null) {
            [$method,$call,$options] = $this->getFromGetVars();
        }
        
        $handler = new DIZGATE_handler();
        
        $results = $handler->{$method}($call,$options);
        var_dump($results);


    }

    protected function gusto($method = null,$call = null, $options = array()) {
        if($method == null) {
            [$method,$call,$options] = $this->getFromGetVars();
        }
        $handler = new GUSTO_handler();

        $results = $handler->{$method}($call,$options);
        var_dump($results);


    }

    protected function getFromGetVars() {

        $MS = explode('.',$this->request['resource']);
        $method = $MS[0];
        $call = $MS[1] ?? null;
        unset($this->request['resource']);
        $options = $this->request;
        return [$method,$call,$options];

    }



}