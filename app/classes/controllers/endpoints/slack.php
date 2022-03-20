<?php
class slack extends PublicController {

    protected function endpoint() {
        $viewmodel = new slackbotModel();
        $viewmodel->endpoint();
    }

    // Slackslash Commands
    protected function dizbot() {
        (new slackSlash_dizbot())->entry();
    }

   protected function info() {
        (new slackSlash_info())->entry();
    }
    protected function kb() {
       
        (new slackSlash_sop())->entry();
    }
    protected function sop() {
        (new slackSlash_sop())->entry();
    }

}