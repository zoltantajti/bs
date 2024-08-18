<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;

class PayPal {
    private $ci;
    public $apiContext;
    public function __construct(){
        $this->ci =& get_instance();
        $this->ci->load->config('paypal');
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->ci->config->item('client_id'),
                $this->ci->config->item('client_secret')
            )
        );
        $this->apiContext->setConfig($this->ci->config->item('settings'));
    }
    public function create_payment($_item,$totalAmount,$currency,$returnUrl,$cancelUrl)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        
        $item = new Item();
        $item->setName($_item[0])->setCurrency($currency)->setQuantity(1)->setPrice($totalAmount);

        $itemList = new ItemList();
        $itemList->setItems([$item]);
        
        $details = new Details();
        $details->setSubtotal($totalAmount);

        $amount = new Amount();
        $amount->setCurrency($currency);
        $amount->setTotal($totalAmount);
        $amount->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)->setItemList($itemList)->setDescription($_item[1])->setInvoiceNumber(uniqid());
        
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($returnUrl)->setCancelUrl($cancelUrl);

        $payment = new Payment();
        $payment->setIntent('sale')->setPayer($payer)->setTransactions([$transaction])->setRedirectUrls($redirectUrls);
        try{
            $payment->create($this->apiContext);
            return $payment->getApprovalLink();
        }catch(Exception $ex){
            log_message('error','PayPal Payment Creation Error: ' . $ex->getMessage());
            return null;
        }
    }
}