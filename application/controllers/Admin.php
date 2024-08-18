<?php
class Admin extends CI_Controller {
    private $data = [
        "p" => null,
        "navbar" => false,
        "folder" => "admin",
        "sidebar" => true,
    ];
    private $errors = [
        'required' => 'A %s mező kitöltése kötelező!',
        'min_length' => 'A {field} mezőnek legalább {param} karakterből kell állnia!',
        'max_length' => 'A {field} mező legfeljebb {param} karakterből állhat!',
        'is_unique' => 'A {field} mező értéke már használatban van!',
        'valid_email' => 'Valós e-mail címet adj meg!'
    ];
    public function __construct(){
        parent::__construct();
        $this->load->model(['Alist','AForm']);
        $this->data['p'] = 'dashboard';
    }

    private function render()
    {
        $this->load->view('index', $this->data);
    }

    public function sellers($f = "list", $id = -1)
    {
        if($f == "list" && $id == -1)
        {   
            $this->data['a'] = '_list';
            $this->data['navbar'] = true;
            $this->data['alist'] = $this->Alist->render('Eladók listája', 'users', 'sellers', true, array(), array(), array(array('edit','info','edit'),array('delete','danger','trash-alt')), false);
        }
        elseif($f == "create" && $id == -1)
        {
            $this->form_validation->set_rules('username','Felhasználónév','trim|required|min_length[6]|is_unique[users.username]',$this->errors);
            $this->form_validation->set_rules('email','E-mail cím','trim|required|valid_email|is_unique[users.email]',$this->errors);
            $this->form_validation->set_rules('password','Jelszó','trim|required|min_length[6]|max_length[64]',$this->errors);
            $this->form_validation->set_rules('realName','Teljes név','trim|required',$this->errors);
            if(!$this->form_validation->run()){
                $this->data['a'] = '_form';
                $this->data['navbar'] = true;
                $this->data['form'] = $this->AForm->render('Új eladó','users',array('method'=>'POST','action'=>'','btnText' => 'Létrehozás'));
            }else{
                $this->User->create();
            };
        }
        elseif($f == "edit" && $id != -1)
        {
            $this->form_validation->set_rules('username','Felhasználónév','trim|required|min_length[6]',$this->errors);
            $this->form_validation->set_rules('email','E-mail cím','trim|required|valid_email',$this->errors);
            $this->form_validation->set_rules('password','Jelszó','trim',$this->errors);
            $this->form_validation->set_rules('realName','Teljes név','trim|required',$this->errors);
            if(!$this->form_validation->run()){
                $this->data['a'] = '_form';
                $this->data['navbar'] = true;
                $values = $this->db->select('*')->from('users')->where('id',$id)->get()->result_array()[0];
                if($values['permission'] > $this->Sess->get('perm','user')){
                    $this->Msg->set('Nem módosíthatsz nálad magasabb rangú felhasználót!','danger');
                    redirect('admin/sellers');
                };
                $this->data['form'] = $this->AForm->render('Új eladó','users',array('method'=>'POST','action'=>'','btnText' => 'Létrehozás'), $values);
            }else{
                $this->User->modify($id);
            };
        }
        elseif($f == "delete" && $id != -1)
        {
            $this->form_validation->set_rules('yes','Megerősítés','callback_confirmation',$this->errors);
            if(!$this->form_validation->run()){
                $this->data['link'] = 'admin/sellers';
                $this->data['a'] = '_question';
                $this->data['q'] = '<div class="alert alert-danger">A törlés visszavonhatatlan következményekkel jár!<br/>Valóban ezt szeretnéd?</div>';            
            }else{
                $this->User->remove($id);
            }
        }
        $this->render();
    }


    public function confirmation()
    {
        if(isset($_POST['yes'])) return true;
        $this->Msg->set('Kérlek erősítsd meg, hogy valóban ezt szeretnéd, vagy lépj vissza!','warning');
        $this->form_validation->set_message('yes','Kérlek erősítsd meg, hogy valóban ezt szeretnéd, vagy lépj vissza!');
        return false;
    }


}