<?php
class User extends CI_Model
{
    public function __construct(){
        parent::__construct();
    }

    public function isLoggedIn(){ if($this->Sess->get('login','user') && $this->Sess->get('uname','user')){ return true; }else{ return false; }; }
    public function protect(){ if(!$this->isLoggedIn()){ redirect('login'); }; }
    public function unProtect(){ if($this->isLoggedIn()){ redirect('index'); }; }
    public function doLogin()
    {
        if(!$this->checkIsLoginAllowed()){
            $record = $this->getBannRecord();
            $this->Msg->set('A bejelentkezés letiltva túl sok hibás belépés miatt <b>' . $record['expire'] . ' időpontig</b>!', 'danger');
            redirect('login');
        };
        $p = $this->input->post();
        $loginAttempt = (!$this->Sess->has("loginAttempt","system")) ? 0 : $this->Sess->get('loginAttempt','system');
        if(isset($p['ci_csrf_token'])) unset($p['ci_csrf_token']); 
        $p['password'] = $this->makePW($p['password']);
        if($this->checkUserIfExists($p['username'])){
            if($this->checkPassword($p['username'],$p['password']))
            {
                $user = $this->db->select('username,realName')->from('admins')->where('username',$p['username'])->where('password',$p['password'])->get()->result_array()[0];
                $this->Sess->set('login', true, 'user');
                $this->Sess->set('uname', $user['username'], 'user');
                $this->Sess->set('realName', $user['realName'], 'user');
                redirect('index');
            }else{
                $loginAttempt += 1;
                $this->authLoginAttempt();                
                $this->Sess->set("loginAttempt", $loginAttempt, "system");
                $this->Msg->set('Sikertelen belépés!', 'danger');
                redirect('login');    
            }
        }else{
            $loginAttempt += 1;
            $this->Sess->set("loginAttempt", $loginAttempt, "system");
            $this->authLoginAttempt();
            $this->Msg->set('Sikertelen belépés!', 'danger');
            redirect('login');
        };
    }

    private function makePW($pass)
    {
        return hash("SHA512/256", $pass . "#VikiOldala?2024");
    }
    private function checkUserIfExists($username)
    {
        $chk = $this->db->select('id')->from('admins')->where('username',$username)->count_all_results();
        return ($chk == 1) ? true : false;
    }
    private function checkPassword($username,$password)
    {
        $chk = $this->db->select('id')->from('admins')->where('username',$username)->where('password',$password)->count_all_results();
        return ($chk == 1) ? true : false;
    }
    private function authLoginAttempt()
    {
        $loginAttempt = (!$this->Sess->has("loginAttempt","system")) ? 0 : $this->Sess->get('loginAttempt','system');
        if($loginAttempt == 3)
        {
            $expire = strtotime(date("Y-m-d H:i:s")) + 60*60;
            $this->db->insert('banns', array('ip' => $_SERVER['REMOTE_ADDR'], "expire" => date("Y-m-d H:i:s", $expire)));
            $this->Sess->set("loginAttempt",0,"system");
        };
    }
    private function checkIsLoginAllowed()
    {
        $chk = $this->db->select('ip')->from('banns')->where('ip', $_SERVER['REMOTE_ADDR'])->count_all_results();
        return ($chk == 1) ? false : true;
    }
    private function getBannRecord()
    {
        $row = $this->db->select('ip,expire')->from('banns')->where('ip',$_SERVER['REMOTE_ADDR'])->get()->result_array()[0];
        return $row;
    }
}

?>