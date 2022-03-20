<?php


class homeModel extends Model
{


    

    public function __construct() {
        
        parent::__construct();


    }

    public function Index() {

        $this->output->setMetaKey('page_title','DIZLOGIC');
        return $this->output;
    }





}
