<?php

abstract class Model
{
    protected $dbh;
    protected $stmt;
    protected $auth;

    protected $output;
    protected $configs;


    public function __construct()
    {
        $this->output = new ModelOutput();
        $this->configs = (new Config($GLOBALS['environment']))->get();
        $this->dbh = new MySQL();
        $this->auth = (new Auth())->revive();
        if(!$this->auth || $this->auth->isAuthenticated() === false) {
            return false;
        }



    }

    public function returnFromWhereYouCame() {
        if(isset($_REQUEST["destination"])){
            header("Location: {$_REQUEST["destination"]}");
        }else if(isset($_SERVER["HTTP_REFERER"])){
            header("Location: {$_SERVER["HTTP_REFERER"]}");
        }else{

        }
    }

    public function action_log($actionType,$performedOnObject,$performedOnId,$description,$data = array()) {
        ActionTracker::log($this->auth->getUserId(),$actionType,$performedOnObject,$performedOnId,$description,$data);
    }
    public function updateAuth() {
        $this->auth = (new Auth())->revive();
    }
    public function getRoles() {
        return $this->auth->getRoles();
    }
    public function mdbx_query() {
        $args = func_get_args();
        $this->stmt =  $this->dbh->query(...$args); //,$args);
        return $this->stmt;
    }
    public function fetch() {
        return $this->stmt->fetchArray();
    }
    public function resultSet() {
        return $this->stmt->fetchAll();
    }
    public function lastInsertId() {
        return $this->stmt->insert_id();
    }





}