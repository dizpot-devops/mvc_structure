<?php
class home extends PublicController {
	protected function Index(){
        $viewmodel = new homeModel();
        $this->ReturnView($viewmodel->Index());

	}



}