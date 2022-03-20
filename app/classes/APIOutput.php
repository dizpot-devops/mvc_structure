<?php


use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class APIOutput
{

    public string $server = 'dizlogic';
    public array $apiName;
    public array $input;
    public array $meta;
    public array $errors;
    public array $results;

    public function __construct()
    {
        $this->apiName = array();
        $this->meta = array();
        $this->input = array();
        $this->results = array();
        $this->errors = array();
    }

    #[Pure] #[ArrayShape(['from' => "array", 'meta' => "array", 'input' => "array", 'results' => "array", 'errors' => "array"])] public function toArray(): array {
        return array(
            'from'=>$this->getApiName(),
            'meta'=>$this->getMeta(),
            'input'=>$this->getInput(),
            'results'=>$this->getResults(),
            'errors'=>$this->getErrors()
        );
    }
    public function toJSON(): bool|string
    {
        return json_encode($this->toArray());
    }

    public function setApiName($calls = array()) {
        if(!is_array($calls)) { $calls = array($calls); }
        $this->apiName = array(
            'server'=>$this->server,
            'calls'=>$calls
        );
    }
    public function getApiName(): array
    {
        return $this->apiName;
    }

    public function setInput($input) {
        $this->input = $input;
    }
    public function getInput(): array
    {
        return $this->input;
    }

    public function insertError($tag,$message) {
        $this->errors[] = array('tag'=>$tag,'message'=>$message);
    }
    public function hasErrors(): bool
    {
        if(count($this->errors) == 0) { return false; }
        return true;
    }
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getResults(): array
    {
        return $this->results;
    }
    public function hasResultsKey($key): bool
    {
        if(isset($this->data[$key]))
            return true;
        return false;
    }

    public function setResultsKey($key, $value)
    {
        if(isset($this->data[$key])){
            $this->data[$key] = $value;
        }
        else{
            $this->data += array($key => $value);
        }
    }

    public function getResultsKey($key){
        if(isset($this->data[$key])) {
            return $this->data[$key];
        }
        else {
            return false;
        }
    }

    public function deleteResultsKey($key){
        if(isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }

    public function getMeta() {
        return $this->meta;
    }

    public function hasMetaKey($key): bool
    {
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
}