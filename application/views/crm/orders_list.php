<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Rendelések listája</h1>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th colspan="2">AZONOSÍTÓ</th>
                    <th class="text-center">
                        <input type="text" id="filterCustomer" class="form-control-filter" placeholder="Megrendelő"/>
                    </th>
                    <th>Fizetendő</th>
                    <th>Kiadás</th>
                    <th>Profit</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php 
                foreach($this->db->select('*')->from('orders')->order_by('payed','ASC')->order_by('status','ASC')->order_by('ordered','ASC')->where('sellerID',$this->Sess->get('id','user'))->get()->result_array() as $k=>$v){
                $class = $this->Orders->getClassByStatus($v['status']);    
                ?>
                <tr>
                    <td class="<?=$class?>"><a href="order/<?=$v['id']?>"><i class="fa-sharp fa-thin fa-folder-open"></i></a></td>
                    <td class="<?=$class?>"><?=$v['id']?> <i>(<?=$this->Orders->getStatus($v['status'])?>)</i></td>
                    <td class="<?=$class?>"><?=$this->Orders->getCustomerNameById($v['customerID'])?></td>
                    <td class="<?=$class?>"><?=number_format($v['totalPay'],0,""," ")?> Ft</td>
                    <td class="<?=$class?>"><?=number_format($v['totalCost'],0,""," ")?> Ft</td>
                    <td class="<?=$class?>"><?=number_format($v['totalProfit'],0,""," ")?> Ft</td>
                    <td class="<?=$class?>">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <?php if($v['status'] == "pending" || $v['status'] == "ordered"){ ?>
                            <button type="button" onClick="setStatus('shipping',<?=$v['id']?>);" class="btn btn-success"><i class="fa-sharp fa-solid fa-truck-fast"></i></button>        
                            <button type="button" onClick="setStatus('cancelled',<?=$v['id']?>);" class="btn btn-danger"><i class="fa-sharp fa-solid fa-square-xmark"></i></button>        
                        <?php }elseif($v['status'] == "shipping"){ ?>
                            <button type="button" onClick="setStatus('awaitPayment',<?=$v['id']?>);" class="btn btn-success"><i class="fa-sharp fa-solid fa-house-building"></i></button>        
                            <button type="button" onClick="setStatus('cancelled',<?=$v['id']?>);" class="btn btn-danger"><i class="fa-sharp fa-solid fa-square-xmark"></i></button>        
                        <?php }elseif($v['status'] == "awaitPayment"){ ?>
                            <button type="button" onClick="setStatus('completed',<?=$v['id']?>);" class="btn btn-success"><i class="fa-sharp fa-solid fa-cart-circle-check"></i></button>        
                            <button type="button" onClick="setStatus('cancelled',<?=$v['id']?>);" class="btn btn-danger"><i class="fa-sharp fa-solid fa-square-xmark"></i></button>        
                        <?php }; ?> 
                    </div>
                    </td>
                </tr>
                <?php }; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="Toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="toast-header">
    <strong class="me-auto" id="toastTitle">Megrendelés</strong>
    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
  <div class="toast-body" id="toastBody"></div>
</div>
</div>