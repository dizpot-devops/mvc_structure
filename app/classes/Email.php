<?php


class Email
{

    private $mail = false;

    public function __construct()
    {
        $this->mail = new PHPMailer();
        $this->mail->SMTPDebug = 0;
        $this->mail->IsSMTP();
        $this->mail->Host = 'smtp.office365.com';
        $this->mail->Port = 587;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'admin@dizgate.com';
        $this->mail->Password = '7383jmd2!';
        $this->mail->SMTPSecure = '';

    }

    public function sendNewEmployeeInvite($firstName, $lastName, $email, $tempPassword) {

        $this->mail->ClearAddresses();
        $this->mail->AddAddress($email, $firstName . " " . $lastName);
        $this->mail->From = "admin@dizgate.com";
        $this->mail->FromName = "DIZGATE";
        $this->mail->Subject = 'Your invitation to DIZGATE';


        $message = '<html><body>';
        $message .= '<h1">Hi ' . $firstName . ' ' .  $lastName . '!</h1>';
        $message .= '<p>An account has been created for you at DIZGATE.  Please go to <a href="' . ROOT_URL . 'users/invite/">' . ROOT_URL . 'users/invite/</a> and log in using the e-mail and temporary password below</p>';
        $message .= '<p>E-mail: ' . $email . '</p>';
        $message .= '<p>Temp Password: ' . $tempPassword . '</p>';
        $message .= '</body></html>';

        $this->mail->Body = $message;
        $this->mail->IsHTML(true);

        $this->send();


    }

    public function send() {
        if(!$this->mail->Send()) {
            echo $this->mail->ErrorInfo;
            //die;
        }
    }
}