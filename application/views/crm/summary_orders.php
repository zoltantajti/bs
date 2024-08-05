<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Rendelések összegzése</h1>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tbody>
                        <tr>
                            <th>KÓD</th>
                            <th>Név</th>
                            <th>Egységár</th>
                            <th>Darabszám</th>
                            <th>Termékek ára</th>
                            <th>Profit / termék</th>
                        </tr>
                    </tbody>
                </thead>
                <tbody>
                <?php $income = 0; $outcome = 0; $profit = 0; foreach($this->Orders->collectAllUnorderedItems() as $k=>$v){ ?>
                    <tr>
                        <td><?=$k?></td>
                        <td><?=$v['name']?></td>
                        <td><?=number_format($v['pricePerDb'],0,""," ")?> Ft</td>
                        <td><?=$v['qty']?></td>
                        <td><?=number_format(($v['pricePerDb'] * $v['qty']),0,""," ")?> Ft</td>
                        <td><?=number_format(($v['pricePerDb'] * $v['qty']) - ($v['costPerDb'] * $v['qty']),0,""," ")?> Ft</td>
                    </tr>
                <?php 
                $income += ($v['pricePerDb'] * $v['qty']);
                $outcome += ($v['costPerDb'] * $v['qty']);
                $profit += ($v['pricePerDb'] * $v['qty']) - ($v['costPerDb'] * $v['qty']);
                }; 
                ?>
                </tbody>
            </table>
            <table class="table">
                <thead>
                    <tr>
                        <th>Befolyó összeg:</th>
                        <th><?=number_format($income,0,""," ")?> Ft</th>
                        <th>Kifizetendő összeg:</th>
                        <th><?=number_format($outcome,0,""," ")?> Ft</th>
                        <th>Profit:</th>
                        <th><?=number_format($profit,0,""," ")?> Ft</th>
                    </tr>
                </thead>
            </table>
            <button type="button" role="button" class="btn btn-outline-success" onClick="submitOrder();">
                <i class="fa-sharp fa-solid fa-cart-circle-check"></i>
                Rendelés leadása!
            </button>
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