<?php
class oauthv2 extends OAuthV2ServerController {


    public function __construct($action,$api_call,$getvars) {
        parent::__construct($action,$api_call,$getvars);

    }
    public function token() {
        $request = OAuth2\Request::createFromGlobals();
        $server = $this->getServer();
        $server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
    }

    public function resource() {
        $server = $this->getServer();

        $request = OAuth2\Request::createFromGlobals();

        if (! $server->verifyResourceRequest($request)) {
            echo "FBN"; exit;
            $server->getResponse()->send();
            die;
        }
        $output = array();
        $output['api_call'] = $this->api_call;
        $response = new OAuth2\Response();

        if(isset($this->api_call)) {
            $parts = explode('.',$this->api_call);
            $api = $parts[0] . 'API';
            $api_method = $parts[1];
            if(!class_exists($api)) {
                echo $api. " does not exist";
            }
            else {
                if(!method_exists($api,$api_method)) {
                    echo $api_method . " does not exist in " . $api;
                }
                else {
                    $apiObj = new $api();
                    $scopes_needed = $apiObj->getScopeRequirementsForMethod($api_method);

                    $scopeUtility = $this->server_scopeUtility;




                    //if (!$server->verifyResourceRequest($request, $response, implode(' ',$scopes_needed))) {
                    // if the scope required is different from what the token allows, this will send a "401 insufficient_scope" error
                    //    $response->send();
                    //     die;
                    // }
              

                    $output = (new $api())->{$api_method}($this->getvars);
                    $response->setParameter('returned_from',$this->api_call);
                    $response->setParameter('passed_in',$this->getvars);
                    $response->setParameter('search_results',$output);
                    $response->send();
                    die;
                }
            }


        }
        $response->setParameter('search_results',$output);
        $response->send();
        die;
    }

    public function authorize() {

        $server = $this->getServer();
        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();

        if (!$server->validateAuthorizeRequest($request, $response)) {
            $response->send();
            die;
        }

        if (empty($_POST)) {
            exit('
<form method="post">
  <label>Do You Authorize TestClient?</label><br />
  <input type="submit" name="authorized" value="yes">
  <input type="submit" name="authorized" value="no">
</form>');
        }


        $is_authorized = ($_POST['authorized'] === 'yes');
        $server->handleAuthorizeRequest($request, $response, $is_authorized);
        if ($is_authorized) {
            // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
            $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
            // exit("SUCCESS! Authorization Code: $code");
        }
        $response->send();
    }
}
