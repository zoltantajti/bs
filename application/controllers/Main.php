<?php

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

class Main extends CI_Controller
{
    private $data = [
        "p" => null,
        "navbar" => false,
        "sidebar" => true,
    ];
    private $errors = [
        'required' => 'A %s mező kitöltése kötelező!',
        'min_length' => 'A {field} mezőnek legalább {param} karakterből kell állnia!',
    ];
    public function __construct(){
        parent::__construct();
        $this->load->database();
        if($this->User->isLoggedIn() && !$this->User->checkHaveSubscription()){
            $this->data['sidebar'] = false;
            if($this->uri->segment(1) != null && $this->uri->segment(1) != "subscribe" && $this->uri->segment(1) != "logout"){
                redirect();
            };
        };
    }
    public function index()
    {
        if(!$this->User->checkHaveSubscription()){
            $this->data['sidebar'] = false;
            $this->data['notice'] = '<div class="alert alert-danger">Nincs, vagy lejárt az előfizetésed! A rendszert addig nem tudod használni, amíg (újra) elő nem fizetsz rá!<br/>Az előfizetést megteheted a bal oldali menüsávban az <b>Előfizetés</b> gombra kattintva!</div>';
        };
        $this->data['p'] = "dashboard";
        $this->data['m'] = "main";
        $this->User->protect();
        $this->render();
    }
    public function subscribe($state = "pre")
    {
        $this->load->library('paypal');
        $this->data['p'] = "dashboard";
        if($state == "pre"){
            $this->form_validation->set_rules('months', 'Időszak', 'trim|required', $this->errors);
            $this->form_validation->set_rules('coupon', 'Kupon', 'trim', $this->errors);
            $this->form_validation->set_rules('totalPrice', 'Végösszeg', 'trim', $this->errors);
            if(!$this->form_validation->run()){
                $expired = $this->db->select('accessExpire')->from('users')->where('id',$this->Sess->get('id','user'))->get()->result_array()[0]['accessExpire'];
                $this->data['allow'] = ($expired <= date("Y-m-d")) ? true : false;
                $this->data['m'] = "subscribe";
            }else{
                redirect('subscribe/pay');
            };
        }elseif($state == "pay"){
            $interval = $this->input->post('months');
            $coupon = $this->input->post('coupon');
            $amount = $this->input->post('totalPrice');
            if(empty($interval) || empty($amount)){ redirect('subscribe'); };
            $intervalItem = $this->db->select('name')->from('subscribe_packs')->where('id',$interval)->get()->result_array()[0]['name'];
            $this->Sess->set('interval', $interval, 'sub');
            $this->Sess->set('coupon', $coupon, 'sub');
            $paymentURL = $this->paypal->create_payment(
                array(
                    "Előfizetés " . $intervalItem,
                    "BetterStyle CRM előfizetés " . $intervalItem
                ),
                $amount,
                'HUF',
                base_url('subscribe/success'),
                base_url('subscribe/cancel')
            );
            if($paymentURL){ 
                redirect($paymentURL); 
            }else{ 
                show_error('Payment creation failed'); 
            };
        }elseif($state == "success"){
            $paymentId = $this->input->get('paymentId');
            $payerId = $this->input->get('PayerID');
            $token = $this->input->get('token');
            if (empty($paymentId) || empty($payerId) || empty($token)) { redirect('subscribe/cancel'); };
            $payment = Payment::get($paymentId,$this->paypal->apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);
            try{
                $result = $payment->execute($execution, $this->paypal->apiContext);
                if($result->getState() == "approved"){
                    $transaction_id = $result->getId();
                    $amount = $result->getTransactions()[0]->getAmount()->getTotal();
                    $currency = $result->getTransactions()[0]->getAmount()->getCurrency();
                    
                    //Calculate interval
                    $today = date("Y-m-d");
                    $intervalID = $this->Sess->get('interval','sub');
                    $intItem = $this->db->select('months')->from('subscribe_packs')->where('id',$intervalID)->get()->result_array()[0]['months'];
                    $expired = date("Y-m-d", strtotime("+".$intItem." months", strtotime($today)));
                    $data = array(
                        'sellerID' => $this->Sess->get('id','user'),
                        'tID' => $transaction_id,
                        'amount' => $amount,
                        'currency' => $currency,
                        'createdAt' => date("Y-m-d H:i:s"),
                        'expiredAt' => $expired
                    );
                    $this->db->set($data)->insert('subscriptions');

                    //handle coupons
                    $coupon = $this->Sess->get('coupon','sub');
                    print_r($_SESSION);
                    if($coupon){
                        $c = $this->db->select('used')->from('subscribe_coupons')->where('code',$coupon)->get()->result_array()[0]['used'];
                        $c++;
                        $this->db->set('used',$c)->where('code',$coupon)->update('subscribe_coupons');
                    };

                    //Update user
                    $this->db->set('accessExpire',$expired)->where('id',$this->Sess->get('id','user'))->update('users');
                    $this->Msg->set('Sikeres fizetés! Az előfizetésed meghosszabbítva <b>' . $expired . '</b> dátumig!');
                    redirect("subscribe/result");
                };
            }catch(Exception $ex){
                log_message('error', 'PayPal Payment Execution Error: ' . $ex->getMessage());
                show_error('Payment Failed');
            }
        }elseif($state == "cancel"){
            $this->Msg->set('A tranzakció sikertelen volt, de az okát nem találtuk meg!');
            redirect("subscribe/result");
        }elseif($state == "result"){
            if($this->Msg->has()){
                $this->data['m'] = "subscribe_msg";
            }else{
                redirect('subscribe');
            }
        }
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
        $this->cart->destroy();
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
            $this->cart->destroy();
            $this->data['order'] = $this->db->select('*')->from('orders')->where('id',$id)->get()->result_array()[0];
            $this->data['customer'] = $this->db->select('*')->from('customers')->where('id',$this->data['order']['customerID'])->get()->result_array()[0]['name'];
            $this->data['m'] = "orders_detail";
            $_prods = json_decode($this->data['order']['products'],true);
            $this->data['prods'] = $_prods;
            $cartData = [];
            foreach($_prods as $product)
            {
                $item = array(
                    "id" => $product['id'],
                    "qty" => $product['qty'],
                    "price" => $product['price'],
                    "name" => $product['name'],
                    'options' => array(
                        'Cost' => $product['cost'],
                        'Profit' => ($product['price'] - $product['price']) * $product['qty']
                    )
                );
                array_push($cartData,$item);
            };
            $this->cart->insert($cartData);
        }
        $this->User->protect();
        $this->render();
    }


    private function render()
    {
        $this->load->view('index', $this->data);
    }
}