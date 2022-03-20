<?php
class Bootstrap{
	private $controller;
	private $action;
	private $request;

	public function __construct($url){
    	   
       		$this->request = $url;
        	if(array_key_exists('url',$url)) {
        	    $urlParts = $this->parseUrl($url);
        	   
        	    $this->controller = array_shift($urlParts);
                $this->action = array_shift($urlParts);
                unset($this->request['url']);
                
       	 	}
        	if($this->controller == ""){
        	    $this->controller = 'home';
        	}
        	if($this->action == ""){
       	 	    $this->action = 'index';
        	}
        	
	}
    public function getController() {
        return $this->controller;
    }
    public function parseUrl($url)
    {

            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        
    }

    public function createController(){

        
        // Check Class
        if(class_exists($this->controller)){
            $parents = class_parents($this->controller);
            // Check Extend
            if(in_array("Controller", $parents)){
                if(method_exists($this->controller, $this->action)){
                    return new $this->controller($this->action, $this->request, false);
                }
                else {
                    $slug = (new slugModel())->slugInfo($this->controller,$this->action);
                    if($slug == false) {
                        echo '<h1>Method does not exist</h1>';
                        return false;
                    }
                    return new $this->controller('slugHandler',$this->request,$slug);
                }
            }

            else if(in_array("OAuthV2ClientController", $parents)) {
                if(method_exists($this->controller, $this->action)){
                    
                    return new $this->controller($this->action, $this->request);
                }
                else {
                    echo '<h1>Does Not Exist</h1>';
                    return false;
                }
            }
            else {
                // Base Controller Does Not Exist
                echo '<h1>Base controller not found</h1>';
                return false;
            }
        } else {
            // Controller Class Does Not Exist
            echo '<h1>Controller class does not exist</h1>';
            return false;
        }
    }
}