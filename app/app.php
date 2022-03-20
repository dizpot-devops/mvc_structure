<?php
session_start();

require __DIR__.'/autoload.php';
$environment = require_once(__DIR__ . '/env.php');
$GLOBALS['environment'] = $environment;

require_once(__DIR__ . '/config.php');
$configs = (new Config($environment))->get();

define('ENV_URL',$configs['site']['url']);
define("ROOT_URL",$configs['site']['url']);
define("MY_DOMAIN",$configs['site']['domain']);
define("MEDIA_DIR", "/assets/images/uploaded/");
define('VTRACK_COOKIE','anb_visitor_id');
define('SESSION_AUTH_KEY','DIZBOT_SESH');


$DB = new MySQL(
    $configs['db']['host'],
    $configs['db']['username'],
    $configs['db']['password'],
    $configs['db']['database']
);


//$IP = str_replace(".","",getIPAddress());

//$DB->query("SELECT n.ip,n.country as ccode,c.country FROM ip2nation n LEFT JOIN ip2nationCountries c ON (n.country = c.code) where n.ip=?",$IP);
//$results = $DB->fetchFirst();
//$ALLOW = false;
//if($results === false){ $ALLOW = true; }
//if($results['country'] == 'United States') { $ALLOW = true; }
$ALLOW = true;
if(!$ALLOW) { echo "NO WAYH"; }
$bootstrap = new Bootstrap($_GET);
$controller = $bootstrap->getController();

$oauth_server_controllers = array('oauthv2','resource','token');
if(in_array($controller,$oauth_server_controllers)) {
    $bootstrap = new OAuthV2Bootstrap($_GET);
    $controller = $bootstrap->createController();
    if ($controller) {
        if ($controller->executeAction() === false) {
            //header('Location: '.ENV_URL.'users/login');
        }
    }
}
else {
    $controller = $bootstrap->createController();
    if ($controller) {
        if ($controller->executeAction() === false) {
            //header('Location: '.ENV_URL.'users/login');
        }
    }
}


function getIPAddress() {
    //whether ip is from the share internet
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    //whether ip is from the proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
//whether ip is from the remote address
    else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
