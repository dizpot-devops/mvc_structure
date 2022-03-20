<?php


class ModelOutput
{
    public $meta;
    public $data;
    public $errors;

    public function __construct()
    {
        $this->meta = array();
        $this->data = array();
        $this->errors = array();
    }

    public function insertError($tag,$message) {
        $this->errors[] = array('tag'=>$tag,'message'=>$message);
    }
    public function hasErrors() {
        if(count($this->errors) == 0) { return false; }
        return true;
    }

    public function hasMetaKey($key){
        if(isset($this->meta[$key]))
            return true;
        return false;
    }

    public function setMetaKey($key, $value)
    {
        if(isset($this->meta[$key])){
            $this->meta[$key] = $value;
        }
        else{
            $this->meta += array($key => $value);
        }
    }

    public function getMetaKey($key){
        if(isset($this->meta[$key])) {
            return $this->meta[$key];
        }
        else {
            return false;
        }
    }

    public function deleteMetaKey($key){
        if(isset($this->meta[$key])) {
            unset($this->meta[$key]);
        }
    }

    public function hasDataKey($key){
        if(isset($this->data[$key]))
            return true;
        return false;
    }

    public function setDataKey($key, $value)
    {
        if(isset($this->data[$key])){
            $this->data[$key] = $value;
        }
        else{
            $this->data += array($key => $value);
        }
    }

    public function getDataKey($key){
        if(isset($this->data[$key])) {
            return $this->data[$key];
        }
        else {
            return false;
        }
    }

    public function deleteDataKey($key){
        if(isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }
}