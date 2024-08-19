<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Csomag részletei</h1>
</div>
<div class="container">
    <div class="row mb-2">
        <div class="col-md-4">
            Csomagszám: <b><?=$package['packageID']?></b>
        </div>
        <div class="col-md-4">
            Beküldve: <b><?=$package['createdAt']?></b>
        </div>
        <div class="col-md-4">
            Átvéve: <b><?=$package['receivedAt']?></b>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-4">
            <div class="btn-group" role="group" aria-label="Basic example">
                <?php $v = $package; if($v['status'] == "pending" || $v['status'] == "ordered"){ ?>
                    <button type="button" onClick="setGroupStatus('shipping',<?=$v['id']?>);" class="btn btn-success"><i class="fa-sharp fa-solid fa-truck-fast"></i></button>        
                    <button type="button" onClick="setGroupStatus('cancelled',<?=$v['id']?>);" class="btn btn-danger"><i class="fa-sharp fa-solid fa-square-xmark"></i></button>        
                <?php }elseif($v['status'] == "shipping"){ ?>
                    <button type="button" onClick="setGroupStatus('awaitPayment',<?=$v['id']?>);" class="btn btn-success"><i class="fa-sharp fa-solid fa-house-building"></i></button>        
                    <button type="button" onClick="setGroupStatus('cancelled',<?=$v['id']?>);" class="btn btn-danger"><i class="fa-sharp fa-solid fa-square-xmark"></i></button>        
                <?php }elseif($v['status'] == "awaitPayment"){ ?>
                    <button type="button" onClick="setGroupStatus('completed',<?=$v['id']?>);" class="btn btn-success"><i class="fa-sharp fa-solid fa-cart-circle-check"></i></button>        
                    <button type="button" onClick="setGroupStatus('cancelled',<?=$v['id']?>);" class="btn btn-danger"><i class="fa-sharp fa-solid fa-square-xmark"></i></button>        
                <?php }; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <b><u>Csomagba foglalt rendelések</u></b>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th colspan="2">AZONOSÍTÓ</th>
                    <th>Megrendelő</th>
                    <th>Fizetendő</th>
                    <th>Kiadás</th>
                    <th>Profit</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php 
                $total = 0;
                $cost = 0;
                $profit = 0;
                foreach($inOrders as $k=>$v){?>
                <tr>
                    <td><a href="order/<?=$v['id']?>"><i class="fa-sharp fa-thin fa-folder-open"></i></a></td>
                    <td><?=$v['id']?> <i>(<?=$this->Orders->getStatus($v['status'])?>)</i></td>
                    <td><?=$this->Orders->getCustomerNameById($v['customerID'])?></td>
                    <td><?=number_format($v['totalPay'],0,""," ")?> Ft</td>
                    <td><?=number_format($v['totalCost'],0,""," ")?> Ft</td>
                    <td><?=number_format($v['totalProfit'],0,""," ")?> Ft</td>
                    <td>
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
                <?php 
                    $total += $v['totalPay'];
                    $cost += $v['totalCost'];
                    $profit += $v['totalProfit'];
                    }; 
                if($this->Finance->calculatePoints($cost) < 100) {
                ?>
                <tr>
                    <td class="bg-body-secondary"></td>
                    <td class="bg-body-secondary" colspan="2">Postaköltség</td>
                    <td class="bg-body-secondary"colspan="1"></td>
                    <td class="bg-body-secondary">750 Ft</td>
                    <td class="bg-body-secondary" colspan="2"></td>
                </tr>
                <?php $cost += 750; $profit -= 750; }; ?>
                <tr>
                    <td></td>
                    <td colspan="1"><b>Összesen:</b> <?=number_format($total,0,""," ")?> Ft</td>
                    <td colspan="1"><b>Fizetendő:</b> <?=number_format($cost,0,""," ")?> Ft</td>
                    <td colspan="2"><b>Profit:</b> <?=number_format($profit,0,""," ")?> Ft</td>
                    <td colspan="2"><b>Pontok:</b> <?=$this->Finance->calculatePoints($cost)?></td>
                </tr>
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