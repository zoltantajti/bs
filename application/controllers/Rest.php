<?php 
class Rest extends CI_Controller {
    public function __construct(){ parent::__construct(); $this->load->database(); }
    public function getCustomerName()
    {
        $request = $this->input->post('name');
        if($this->db->select('name')->from('customers')->where('name',$request)->count_all_results() == 1){
            echo(json_encode(['customer'=>'found']));
        }else{
            echo(json_encode(['customer'=>'not-found']));
        };
    }
    public function getProductByCode()
    {
        $request = $this->input->post('code');
        if($this->db->select('prodCode')->from('products')->where('prodCode',$request)->count_all_results() == 1)
        {  
            $product = $this->db->select('*')->from('products')->where('prodCode',$request)->get()->result_array()[0];
            echo(json_encode([
                'product' => 'found',
                'details' => [
                    'code' => $product['prodCode'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'cost' => $product['cost']
                ]
            ]));
        }
        else
        {
            echo(json_encode(['product'=>'not-found']));
        }
    }
    public function updateProduct()
    {
        $request = $this->input->post();
        if($this->db->select('prodCode')->from('products')->where('prodCode',$request['prodCode'])->count_all_results() == 1)
        {//UPDATE
            $this->db->update('products', $request, array('prodCode' => $request['prodCode']));
        }else{//INSERT
            $this->db->insert('products', $request);
        };
    }
    public function addToCart()
    {
        $request = $this->input->post();
        $item = json_decode($request['item'],true);
        $cItem = array(
            'id'      => $item['code'],
            'qty'     => $item['qty'],
            'price'   => $item['price'],
            'name'    => $item['name'],
            'options' => array(
                'Cost' => $item['cost'], 
                'Profit' => $item['profit']
            )
        );
        $this->cart->insert($cItem);        
    }
    public function updateItem()
    {
        $p = $this->input->post();
        $row = $this->cart->contents($p['rowid']);
        $qty = $row[$p['rowid']]['qty'];
        if($p['method'] === '+'){
            $qty++;
        }elseif($p['method'] === '-'){
            $qty--;
        };
        $data = array(
            'rowid' => $p['rowid'],
            'qty' => $qty
        );
        $this->cart->update($data);        
    }
    public function removeItem()
    {
        $rowid = $this->input->post('rowid');
        $this->cart->remove($rowid);
    }
    public function drawCartItems()
    {
        $cItems = $this->cart->contents();
        echo(json_encode($cItems));
    }
    public function finishOrder()
    {
        //Make or select customer from name
        $customerName = $this->input->post('customer');
        if($this->db->select('id')->from('customers')->where('name',$customerName)->count_all_results() == 0){ $this->db->insert('customers',array('name' => $customerName)); };
        $cID = $this->db->select('id')->from('customers')->where('name',$customerName)->get()->result_array()[0]['id'];
        
        //handle pay values
        $totalPay = 0;
        $totalCost = 0;
        $totalProfit = 0;

        //get cart contents
        $cart = $this->cart->contents();
        $cartItems = [];
        foreach($cart as $k=>$v){
            $_totalPay = ($v['price'] * $v['qty']);
            $_totalCost = ($v['options']['Cost'] * $v['qty']);
            $_totalProfit = $_totalPay - $_totalCost;
            $totalPay += $_totalPay;
            $totalCost += $_totalCost;
            $totalProfit += $_totalProfit;
            $item = [
                "id" => $v['id'],
                "name" => $v['name'],
                "price" => $v['price'],
                "cost" => $v['options']['Cost'],
                "qty" => $v['qty']
            ];
            array_push($cartItems,$item);
        };
        
        
        $data = array(
            "customerID" => $cID,
            "orderCreated" => date("Y-m-d H:i:s"),
            "ordered" => 0,
            "payed" => 0,
            "status" => "pending",
            "products" => json_encode($cartItems),
            "totalPay" => $totalPay,
            "totalCost" => $totalCost,
            "totalProfit" => $totalProfit
        );

        $this->db->insert('orders',$data);
        $this->cart->destroy();

        echo("OK");
    }

    public function submitOrder()
    {
        $first = date("Y-m-01");
        $last = date("Y-m-t");
        
        /*Create package info*/
        $orderIDs = [];
        foreach($this->db->select('id')->from('orders')->where('ordered',0)->where('orderCreated >=', $first)->where('orderCreated <= ', $last)->get()->result_array() as $order)
        {
            $a = $order['id'];
            array_push($orderIDs, $a);
        };
        $struct = [
            'packageID' => uniqid(),
            'orders' => json_encode($orderIDs),
            'createdAt' => date("Y-m-d H:i:s")
        ];
        $this->db->insert('packages',$struct);        
        
        /*Update orders*/
        $this->db->set(array(
            'ordered' => 1,
            'status' => 'ordered',
            'orderSubmitted' => date("Y-m-d H:i:s")
        ))->where('ordered',0)->where('orderCreated >=', $first)->where('orderCreated <= ', $last)->update('orders');
        echo("OK");
    }

    public function confirmOrder()
    {
        $id = $this->input->post('ID');
        $pack = $this->db->select('orders')->from('packages')->where('packageID',$id)->get()->result_array()[0]['orders'];
        foreach(json_decode($pack,true) as $order)
        {
            $this->db->set(array('status' => 'awaitPayment'))->where('id',$order)->where_not_in('status','cancelled')->update('orders');
        };
        $this->db->update('packages',array('receivedAt' => date('Y-m-d H:i:s')), array('packageID' => $id));
        echo("OK");
    }
    public function modifyOrderStatus()
    {
        $target = $this->input->post('target');
        $id = $this->input->post('id');
        $this->db->set(array('status'=>$target))->where('id',$id)->update('orders');
        echo("OK");
    }
    public function setGroupStatus()
    {
        $target = $this->input->post('target');
        $id = $this->input->post('id');
        $orders = $this->db->select('orders')->from('packages')->where('id',$id)->get()->result_array()[0]['orders'];
        foreach(json_decode($orders,true) as $order)
        {
            $this->db->set(array('status' => $target))->where('id',$order)->where_not_in('status','cancelled')->update('orders');
        };
        $this->db->set(array('status' => $target))->where('id',$id)->update('packages');
        if($target == "awaitPayment"){
            $this->db->set(array('receivedAt' => date("Y-m-d H:i:s")))->where('id',$id)->update('packages');
        };
        echo("OK");   
    }
}; 
?>