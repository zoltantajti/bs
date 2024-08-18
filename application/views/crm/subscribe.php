<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Előfizetés</h1>
</div>
<div class="container">
    <div class="row">
        <?php if($allow){ ?>
        <div class="col-md-12 mb-3">
            <?=form_open('subscribe/pay'); ?>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa-light fa-calendar-days"></i></span>
                <select name="months" id="months" class="form-control" onchange="calcPrice()">
                    <option selected disabled>Válassz időszakot</option>
                    <?php foreach($this->db->select('id,name,price')->from('subscribe_packs')->order_by('id','ASC')->get()->result_array() as $item) { ?>
                    <option value="<?=$item['id']?>"><?=$item['name']?> (<?=number_format($item['price'],0,""," ")?> Ft)</option>
                    <?php }; ?>
                </select>
                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-percent"></i></span>
                <input type="text" name="coupon" onkeyup="checkCoupon()" value="" placeholder="Ha van kuponkódod, itt add meg!" id="coupon" class="form-control" />
                <input type="text" readonly name="totalPrice" value="" id="price" class="form-control" />
                <span class="input-group-text" id="basic-addon1">Ft</span>
                <button type="submit" name="submit" value="1" class="btn btn-outline-success"><i class="fa-brands fa-cc-paypal"></i></button>
            </div>
            <?=form_close()?>
        </div>
        <?php }else{ ?>
        <div class="col-md-12 mb-3">
            <div class="alert alert-success">
                <b>Már rendelkezel érvényes előfizetéssel!</b><br/>
                Kérlek, várd meg, amíg lejár, mielőtt megújítod!
            </div>
        </div>
        <?php }; ?>
    </div>
</div>
<div class="toast-container position-fixed top-0 end-0 p-3">
<div id="Toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="toast-header">
    <strong class="me-auto" id="toastTitle">Megrendelés</strong>
    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
  <div class="toast-body" id="toastBody"></div>
</div></div>