<?php
class Sess extends CI_Model {
    public function __construct(){ parent::__construct(); }
    public function has($key = "", $chain = ""){
        $chain = explode("/", $chain);
        $value = $_SESSION;
        foreach($chain as $segment){
            if(isset($value[$segment])){
                $value = $value[$segment];
            }else{
                return false;
            };
        };
        return isset($value[$key]) ? true : false;
    }
    public function get($key = "", $chain = "")
    {
        $chain = explode("/", $chain);
        $value = $_SESSION;
        foreach($chain as $segment){
            if(isset($value[$segment])){
                $value = $value[$segment];
            }else{
                return false;
            };
        };
        return isset($value[$key]) ? $value[$key] : "";
    }
    public function set($key = "", $value = "", $chain = "")
    {
        $chain = explode("/",$chain);
        $ref = &$_SESSION;
        foreach($chain as $segment)
        {
            if(!isset($ref[$segment]) || !is_array($ref[$segment])){
                $ref[$segment] = [];
            };
            $ref = &$ref[$segment];
        };
        $ref[$key] = $value;
    }
    public function rem($key = "", $chain = "")
    {
        $chain = explode("/", $chain);
        $value = &$_SESSION;
        foreach($chain as $segment){
            if(isset($value[$segment])){
                $value = $value[$segment];
            }else{
                return false;
            };
        };
        if(isset($value[$key])){ unset($value[$key]); };     
    }
    public function destroy()
    {
        session_destroy();
    }
    

}