<?php
class OAuthV2Bootstrap{
    private $controller = 'oauthv2';
    private $action;
    private $request;
    private $api_call;
    private $sub_calls = array();
    public function __construct($url){
        $this->request = $url;

        if(array_key_exists('url',$url)) {
            $urlParts = $this->parseUrl($url);
            
            $this->controller = array_shift($urlParts);

            $this->action = array_shift($urlParts);
            $this->api_call = array_shift($urlParts);
            for($i=0; $i<count($urlParts); $i++) {
                $this->sub_calls[] = array_shift($urlParts);
            }
            unset($this->request['url']);
        }

        
    }

    public function parseUrl($url)
    {
        return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
    }

    public function createController(){
        // Check Class
         //echo "DEBUG:" $this->controller . "||" . $this->action;
        if(class_exists($this->controller)){
            $parents = class_parents($this->controller);
            // Check Extend
            if(in_array("OAuthV2ServerController", $parents)){
               
                if(method_exists($this->controller, $this->action)){
                    // echo "CONTROLLER=" . $this->controller . "<br>";
                    // echo "ACTION=" . $this->action . "<br>";
                    // echo "API_CALL=" . $this->api_call . "<br>";
                    // echo "SUB_CALLS========<br>";
                    // var_dump($this->sub_calls) . "<br>";
                    // echo "GET_VARS========<br>";
                    // var_dump($this->request) . "<br>";
                    return new $this->controller($this->action, $this->api_call,$this->sub_calls,$this->request);
                }
                else {
                    echo '<h1>Action not found</h1>';
                    return false;
                }
            } else {
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