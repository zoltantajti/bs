<?php
class BSApi extends CI_Controller {

    protected $publicString = "https://www.betterstyle.hu/search?controller=search&orderby=position&search_query=";
    protected $bsLoginLink = "https://sp.betterstyle.hu/sp/login_post.htm";
    protected $privateString = "https://sp.betterstyle.hu/sp/account/order/hub?text=";

    public function __construct(){ parent::__construct(); $this->load->database(); }

    public function GetPublicProductNameAndPublicPrice()
    {
        $result = [];
        $code = $this->input->post('code');
        $html = file_get_contents($this->publicString . $code);
        $this->load->library('htmlparser', array($html));
        $price = $this->htmlparser->getContentByClass('price product-price');
        $name = $this->htmlparser->getContentByClass('grid-name');
        $result['price'] = str_replace(array(' ',',00','Ft'),array('','',''),$price[0]);
        $result['name'] = $name[0];
        echo(json_encode($result));
    }
}