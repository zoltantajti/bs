<?php
class Orders extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function collectAllUnorderedItems()
    {
        $items = [];
        foreach($this->db->select('products')->from('orders')->where('ordered',0)->where('sellerID',$this->Sess->get('id','user'))->get()->result_array() as $k=>$v)
        {
            foreach(json_decode($v['products'],true) as $key=>$item)
            {
                if(isset($items[$item['id']]))
                {
                    $items[$item['id']]['qty'] += $item['qty'];
                }else{
                    $items[$item['id']] = [
                        'name' => $item['name'],
                        'qty' => $item['qty'],
                        'pricePerDb' => $item['price'],
                        'costPerDb' => $item['cost'],
                    ];
                };
            };
        };
        return $items;
    }

    public function listPackages()
    {
        return $this->db->select('packageID,createdAt,receivedAt')->from('packages')->order_by('receivedAt','ASC')->order_by('createdAt','DESC')->where('sellerID',$this->Sess->get('id','user'))->get()->result_array();
    }
    public function getPackageById($id)
    {
        return $this->db->select('*')->from('packages')->where('packageID',$id)->where('sellerID',$this->Sess->get('id','user'))->get()->result_array()[0];
    }
    public function listOrdersByIds($id)
    {
        $result = [];
        foreach($id as $key)
        {
            $row = $this->db->select('id,customerID,orderCreated,products,totalPay,totalCost,totalProfit,status')->from('orders')->where('id',$key)->where('sellerID',$this->Sess->get('id','user'))->get()->result_array()[0];
            array_push($result,$row);
        };
        return $result;
    }
    public function getCustomerNameById($id)
    {
        return $this->db->select('name')->from('customers')->where('id',$id)->where('sellerID',$this->Sess->get('id','user'))->get()->result_array()[0]['name'];
    }
    public function getStatus($s)
    {
        $ret = "";
        switch($s)
        {
            case "pending": $ret = "Függőben"; break;
            case "ordered": $ret = "Megrendelve"; break;
            case "shipping": $ret = "Szállítás alatt"; break;
            case "awaitPayment": $ret = "Fizetésre vár"; break;
            case "completed": $ret = "Teljesítve"; break;
            case "cancelled": $ret = "Meghiúsult"; break;
        };
        return $ret;
    }
    public function getClassByStatus($s)
    {
        $ret = "";
        switch($s)
        {
            case "pending": $ret = ""; break;
            case "ordered": $ret = ""; break;
            case "shipping": $ret = ""; break;
            case "awaitPayment": $ret = "bg-warning"; break;
            case "completed": $ret = "bg-success text-white"; break;
            case "cancelled": $ret = "bg-danger text-white"; break;
        };
        return $ret;
    }
}   