<?php
class Finance extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCurrentMonth()
    {
        $first = date("Y-m-01");
        $last = date("Y-m-t");
        $income = 0;
        $outcome = 0;
        $profit = 0;
        foreach($this->db->select('status,totalPay,totalCost,totalProfit')->from('orders')->where('sellerID',$this->Sess->get('id','user'))->where('ordered',1)->where('orderCreated >= ', $first)->where('orderCreated <= ', $last)->where('status','completed')->or_where('status','awaitPayment')->or_where('status','shipping')->or_where('status','cancelled')->get()->result_array() as $row){
            if($row['status'] == "cancelled"){
                $income += 0;
                $outcome += $row['totalCost'];
                $profit += (0 - $row['totalCost']);
            }
            elseif($row['status'] == "completed")
            {
                $income += $row['totalPay'];
                $outcome += $row['totalCost'];
                $profit += $row['totalProfit'];
            };
        };
        return array('income' => $income, 'outcome' => $outcome, 'profit' => $profit);
    }
    public function getAllTime()
    {
        $income = 0;
        $outcome = 0;
        $profit = 0;
        foreach($this->db->select('status,totalPay,totalCost,totalProfit')->from('orders')->where('sellerID',$this->Sess->get('id','user'))->where('ordered',1)->where('status','completed')->or_where('status','awaitPayment')->or_where('status','shipping')->or_where('status','cancelled')->get()->result_array() as $row){
            if($row['status'] == "cancelled"){
                $income += 0;
                $outcome += $row['totalCost'];
                $profit += (0 - $row['totalCost']);
            }
            elseif($row['status'] == "completed")
            {
                $income += $row['totalPay'];
                $outcome += $row['totalCost'];
                $profit += $row['totalProfit'];
            };
        };
        return array('income' => $income, 'outcome' => $outcome, 'profit' => $profit);
    }

    public function calculatePoints($amount){
        $points = ceil($amount / 200);
        return $points - 1;
    }
}