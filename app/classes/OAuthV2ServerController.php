<?php
abstract class OAuthV2ServerController
{
    private $configs;
    private $dbh;
    private $dsn;
    private $db_username;
    private $db_password;
    private $storage;
    private $server;
    public $action;
    public $api_call;
    public $sub_calls = array();
    public $getvars;

    public $server_configs = array(
        'use_jwt_access_tokens'        => false,
        'jwt_extra_payload_callable' => null,
        'store_encrypted_token_string' => true,
        'use_openid_connect'       => false,
        'id_lifetime'              => 3600,
        'access_lifetime'          => 3600,
        'www_realm'                => 'Service',
        'token_param_name'         => 'access_token',
        'token_bearer_header_name' => 'Bearer',
        'enforce_state'            => false,
        'require_exact_redirect_uri' => true,
        'allow_implicit'           => false,
        'allow_credentials_in_request_body' => true,
        'allow_public_clients'     => true,
        'always_issue_new_refresh_token' => true,
        'unset_refresh_token_after_use' => true,
    );
    public $refresh_token_configs = array(
        'always_issue_new_refresh_token' => true,
        'unset_refresh_token_after_use' => true
    );

    private $server_tokenType;
    private $server_clientAssertionType;

    public $server_grantTypes = array();
    public $server_responseTypes = array();
    public $server_scopeUtility;

    public function __construct($action,$api_call,$sub_calls = [],$getvars = [])
    {


        $this->action = $action;
        $this->api_call = $api_call;
        $this->sub_calls = $sub_calls;
        $this->getvars = $getvars;
     

        $this->configs = (new Config($GLOBALS['environment']))->get();
        $this->dbh = new MySQL();
        $this->dsn = 'mysql:dbname=' . $this->configs['db']['database'] . ';host=' . $this->configs['db']['host'];
        $this->db_username = $this->configs['db']['username'];
        $this->db_password = $this->configs['db']['password'];
        $this->storage = new OAuth2\Storage\Pdo(array('dsn' => $this->dsn, 'username' => $this->db_username, 'password' => $this->db_password));

        $this->server_tokenType = new OAuth2\TokenType\Bearer();
        $this->server_scopeUtility = new OAuth2\Scope($this->storage);
        $this->server_clientAssertionType = new OAuth2\ClientAssertionType\HttpBasic($this->storage);


        $this->server = new OAuth2\Server($this->storage,$this->server_configs,$this->server_grantTypes,$this->server_responseTypes,$this->server_tokenType,$this->server_scopeUtility,$this->server_clientAssertionType);
        // $this->server = new OAuth2\Server($this->storage,$this->server_configs,$this->server_grantTypes,$this->server_responseTypes,$this->server_tokenType,$this->server_scopeUtility,$this->server_clientAssertionType);

        $this->server->addGrantType(new OAuth2\GrantType\ClientCredentials($this->storage));
        $this->server->addGrantType(new OAuth2\GrantType\AuthorizationCode($this->storage));
        $this->server->addGrantType(new OAuth2\GrantType\RefreshToken($this->storage,$this->refresh_token_configs));
        $this->server->addResponseType(new OAuth2\ResponseType\AuthorizationCode($this->storage,array('auth_code_lifetime' => 120)),'code');
        $this->server->addResponseType(new OAuth2\ResponseType\AccessToken($this->storage,$this->storage),'token');

    }

    public function getDbh()
    {
        return $this->dbh;
    }

    public function getStorage()
    {
        return $this->storage;
    }

    public function getServer()
    {
        return $this->server;
    }


    public function executeAction(){
        return $this->{$this->action}();
    }
}