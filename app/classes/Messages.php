<?php


class Messages
{
    public static function setMessage($msg,$type) {
        $_SESSION['MESSAGE'][] = array($msg,$type);
    }
    public static function hasMessages() {
        return count($_SESSION['MESSAGE']);
    }
    public static function displayMessage() {

        if(isset($_SESSION['MESSAGE'])) {
            for($i=0; $i<count($_SESSION['MESSAGE']); $i++) {
                echo '<div class="alert alert-';

                if ($_SESSION['MESSAGE'][$i][1] == 'error') {
                    echo 'danger';
                } else {
                    echo 'success';
                }
                echo '">';


                echo $_SESSION['MESSAGE'][$i][0] . "<br/>";


                echo '</div>';
            }
            unset($_SESSION['MESSAGE']);
        }

    }
}