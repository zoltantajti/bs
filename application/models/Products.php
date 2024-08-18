<?php
class Products extends CI_Model 
{
    public function __construct(){ parent::__construct(); }

    public function create()
    {
        $p = $this->input->post();
        if(isset($p['submit'])) unset($p['submit']);
        $p['sellerID'] = $this->Sess->get('id','user');
        $this->db->set($p)->insert('products');
        $this->Msg->set('Sikeres adatbevitel','success');
        redirect('products');
    }
    public function update($id)
    {
        $p = $this->input->post();
        if(isset($p['submit'])) unset($p['submit']);
        $this->db->set($p)->where('id',$id)->where('sellerID',$this->Sess->get('id','user'))->update('products');
        $this->Msg->set('Sikeres adatbevitel','success');
        redirect('products');
    }
    public function delete($id)
    {
        if($this->db->select('id')->from('products')->where('id',$id)->where('sellerID',$this->Sess->get('id','user'))->count_all_results() == 1){
            $this->db->where('id',$id)->where('sellerID',$this->Sess->get('id','user'))->delete('products');
            $this->Msg->set('A törlés sikeres','success');
            redirect('products');
        }else{
            $this->Msg->set('A rekord nem található','danger');
            redirect('products');
        }
    }
}