<?php
class dizgate extends PublicController {


    private $client_id = '';
    private $client_secret = '';
    private $redirect_uri = '';

    public function __construct($action, $request, $slug, $auth=true)
    {
        parent::__construct($action, $request, $slug, $auth=true);
    }

    protected function Index(){

	}





}