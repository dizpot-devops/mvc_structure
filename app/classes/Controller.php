<?php
abstract class Controller{
	protected $request;
	protected $action;

	protected $auth;

	public function __construct($action, $request, $auth = true){
		$this->action = $action;
		$this->request = $request;


		if($auth) {
            $this->auth = (new Auth())->revive();
            if(! $this->auth->isAuthenticated()) {
                header("Location: " . ROOT_URL . 'users/login/');
            }
        }


	}

	public function executeAction(){
	    $auth = (new Auth())->revive();
	    if(!$auth) {
            //echo json_encode(array('requiresAuthentication'=>true,'authenticated'=>false));
            return false;
        }
		return $this->{$this->action}();
	}
    public function updateAuth() {
        $this->auth = (new Auth())->revive();
    }
	protected function returnView($viewmodel, $fullview = true){

	    if($viewmodel === 'unauthorized') {
            require(__DIR__ . '/../views/unauthorized.php');
        }
        else {
            $view = '' . strtolower(get_class($this)) . '/' . $this->action . '.php';

            if ($fullview === true) {
                require(__DIR__ . '/../views/admin.php');
            } else if ($fullview === false) {
                require(__DIR__ . '/../views/' . $view);
            } else {
                if ($this->auth->isAuthenticated()) {
                    echo json_encode($viewmodel);
                } else {
                    echo json_encode(array('requiresAuthentication' => true, 'authenticated' => false));
                }
            }
        }
	}
}