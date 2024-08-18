<?php
class Customer extends CI_Model {
    public function __construct(){
        parent::__construct();
    }

    public function create()
    {
        $p = $this->input->post();
        if(isset($p['submit'])) unset($p['submit']);
        $p['sellerID'] = $this->Sess->get('id','user');
        $this->db->set($p)->insert('customers');
        $this->Msg->set('Sikeres adatbevitel','success');
        redirect('customers');
    }
    public function update($id)
    {
        $p = $this->input->post();
        if(isset($p['submit'])) unset($p['submit']);
        $this->db->set($p)->where('id',$id)->where('sellerID',$this->Sess->get('id','user'))->update('customers');
        $this->Msg->set('Sikeres adatbevitel','success');
        redirect('customers');
    }
    public function delete($id)
    {
        if($this->db->select('id')->from('customers')->where('id',$id)->where('sellerID',$this->Sess->get('id','user'))->count_all_results() == 1){
            $this->db->where('id',$id)->where('sellerID',$this->Sess->get('id','user'))->delete('customers');
            $this->Msg->set('A törlés sikeres','success');
            redirect('customers');
        }else{
            $this->Msg->set('A rekord nem található','danger');
            redirect('customers');
        }
    }
}