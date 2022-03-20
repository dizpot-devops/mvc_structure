<?php


class PublicController extends Controller
{

    public function __construct($action, $request)
    {
        parent::__construct($action, $request, false);
    }

    protected function returnView($viewmodel, $fullview = true){
        $view = ''. strtolower(get_class($this)). '/' . $this->action. '.php';

        if($fullview === true){
            require(__DIR__ . '/../views/public.php');
        }
        else if($fullview == 'api') {

            echo json_encode($viewmodel);
        }
        else {
            require(__DIR__ . '/../views/' . $view);
        }
    }

    public function executeAction(){
        return $this->{$this->action}();
    }
}