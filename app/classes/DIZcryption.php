<?php


class DIZcryption
{
    private $ciphering = 'AES-128-CTR';
    private $key = '9r7fhJkakjd75';
    private $options = 0;
    private $iv = '8945843878574373';
    private $tag;
    private $aad;
    private $tag_length;

    public function __construct()
    {

    }

    public function encrypt($string) {
        return openssl_encrypt($string,$this->ciphering,$this->key,$this->options,$this->iv);
    }
    public function decrypt($enc_string) {
        return openssl_decrypt($enc_string,$this->ciphering,$this->key,$this->options,$this->iv);
    }
}