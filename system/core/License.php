<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class License {
    protected $lic;
    public function __construct(){
        /*$this->lic = file_get_contents("https://tajtizoltan.hu/licenses/dayz24.lic");
        $this->lic = json_decode($this->lic,true);
        if(!$this->checkValid() || !$this->checkDomain()){
            die('License error! The site has been locked this reason: ' . $this->lic['message']);
        }else{
            DEFINE("LICENSE","OK");
        }*/
        DEFINE("LICENSE","OK");
    }

    private function checkValid(){
        return ($this->lic['license'] === "valid") ? true : false;
    }
    private function checkDomain(){
        if(in_array($_SERVER['HTTP_HOST'], $this->lic['allowedDomains'])){
            return true;
        }else{
            return false;
        };
    }
}