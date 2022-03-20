<?php
class token
    extends AbstractResource {


    public function __construct($action, $request)
    {
        parent::__construct($action,$request);
    }


    protected function dizgate() {
        $url = 'https://dizgate.com/apiv2/resource/sop.search';
    
    }

}