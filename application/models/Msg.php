<?php
class Msg extends CI_Model {
    protected $pattern = "<div class='alert alert-{class}' role='alert' onClick='$(this).hide();'>{msg}</div>";

    public function __construct(){ parent::__construct(); }

    public function set($msg, $class = "info"){
        $this->Sess->set("msg", array('message'=>$msg,'css'=>$class), "system");        
    }

    public function get(){
        if($this->Sess->has("msg","system")){
            $msg = str_replace(array("{msg}","{class}"), array($this->Sess->get("message", "system/msg"),$this->Sess->get("css","system/msg")), $this->pattern);
            unset($_SESSION['system']['msg']);            
            return $msg;
        };
    }
}