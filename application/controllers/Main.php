<?php
class Main extends CI_Controller
{
    private $data = [
        "p" => null,
        "navbar" => false
    ];
    private $errors = [
        'required' => 'A %s mező kitöltése kötelező!',
        'min_length' => 'A {field} mezőnek legalább {param} karakterből kell állnia!',
    ];
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }



    public function index()
    {
        $this->data['p'] = "dashboard";
        $this->data['m'] = "main";
        $this->User->protect();
        $this->render();
    }
    public function login()
    {
        $this->User->unProtect();
        $this->form_validation->set_rules('username', 'Felhasználónév', 'trim|required', $this->errors);
        $this->form_validation->set_rules('password', 'Jelszó', 'trim|required|min_length[6]', $this->errors);
        if(!$this->form_validation->run())
        {
            $this->load->view('login');
        }else{
            $this->User->doLogin();
        }
    }
    public function logout()
    {
        $this->User->protect();
        $this->Sess->destroy();
        redirect('login');
    }

    public function new_order()
    {
        $this->data['p'] = "dashboard";
        $this->data['m'] = "new_order";
        $this->User->protect();
        $this->render();
    }
    public function orders_summary()
    {
        $this->data['p'] = "dashboard";
        $this->data['m'] = "summary_orders";
        $this->User->protect();
        $this->render();
    }
    public function packages($id = -1)
    {
        $this->data['p'] = "dashboard";
        if($id == -1){
            $this->data['m'] = "packages_list";
        }else{
            $this->data['m'] = "packages_details";
            $this->data['package'] = $this->Orders->getPackageById($id);
            $this->data['inOrders'] = $this->Orders->listOrdersByIds(json_decode($this->data['package']['orders'],true));
        }
        $this->User->protect();
        $this->render();
    }
    public function order($id = -1)
    {
        $this->data['p'] = "dashboard";
        if($id == -1){
            $this->data['m'] = "orders_list";
        }else{
            $this->data['m'] = "orders_detail";
        }
        $this->User->protect();
        $this->render();
    }


    private function render()
    {
        $this->load->view('index', $this->data);
    }
}