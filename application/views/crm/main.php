<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">CRM kezdőoldal</h1>
</div>
<div class="container">
    <div class="row mb-3">
        <div class="col-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa-sharp fa-solid fa-cart-circle-exclamation fa-4x"></i><br/>
                    Függőben lévő megrendelések<br/>
                    <span class="badge bg-warning text-black"><?=$this->db->select('id')->from('orders')->where('ordered',0)->where('sellerID',$this->Sess->get('id','user'))->count_all_results()?> db</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa-sharp fa-solid fa-cart-circle-arrow-up fa-4x"></i><br/>
                    Elküldött megrendelések<br/>
                    <span class="badge bg-warning text-black"><?=$this->db->select('id')->from('orders')->where('ordered',1)->where('status','ordered')->where('sellerID',$this->Sess->get('id','user'))->count_all_results()?> db</span>
                    <a href="<?=site_url('packages')?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa-sharp fa-solid fa-cart-shopping-fast fa-4x"></i><br/>
                    Szállítás alatt lévő csomagok<br/>
                    <span class="badge bg-warning text-black"><?=$this->db->select('id')->from('orders')->where('ordered',1)->where('status','shipping')->where('sellerID',$this->Sess->get('id','user'))->count_all_results()?> db</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa-sharp fa-solid fa-cart-circle-arrow-down fa-4x"></i><br/>
                    Átvételre váró csomagok<br/>
                    <span class="badge bg-warning text-black"><?=$this->db->select('id')->from('orders')->where('ordered',1)->where('status','awaitPayment')->where('sellerID',$this->Sess->get('id','user'))->count_all_results()?> db</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa-sharp fa-solid fa-cart-circle-check fa-4x"></i><br/>
                    Teljesített megrendelések<br/>
                    <span class="badge bg-warning text-black"><?=$this->db->select('id')->from('orders')->where('ordered',1)->where('status','completed')->where('sellerID',$this->Sess->get('id','user'))->count_all_results()?> db</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa-sharp fa-solid fa-cart-circle-xmark fa-4x"></i><br/>
                    Meghiúsult megrendelések<br/>
                    <span class="badge bg-warning text-black"><?=$this->db->select('id')->from('orders')->where('ordered',1)->where('status','cancelled')->where('sellerID',$this->Sess->get('id','user'))->count_all_results()?> db</span>
                </div>
            </div>
        </div>
    </div>
    <?php
        $monthly = $this->Finance->getCurrentMonth();
        $allTime = $this->Finance->getAllTime();
    ?>
    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa-sharp fa-solid fa-lightbulb-dollar fa-4x"></i><br/>
                    <b>HAVI BEVÉTEL</b><br/>
                    <span class="badge bg-warning text-black"><b><?=$monthly['income']?> Ft</b></span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa-sharp fa-solid fa-hand-holding-dollar fa-4x"></i><br/>
                    <b>HAVI KIADÁS</b><br/>
                    <span class="badge bg-danger text-white"><b><?=$monthly['outcome']?> Ft</b></span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa-sharp fa-solid fa-sack-dollar fa-4x"></i><br/>
                    <b>HAVI PROFIT</b><br/>
                    <span class="badge <?=($monthly['profit'] > 0) ? "bg-success text-white" : "bg-warning"?>"><b><?=$monthly['profit']?> Ft</b></span>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa-sharp fa-solid fa-lightbulb-dollar fa-4x"></i><br/>
                    <b>ÖSSZES BEVÉTEL</b><br/>
                    <span class="badge bg-warning text-black"><b><?=$monthly['income']?> Ft</b></span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa-sharp fa-solid fa-hand-holding-dollar fa-4x"></i><br/>
                    <b>ÖSSZES KIADÁS</b><br/>
                    <span class="badge bg-danger text-white"><b><?=$monthly['outcome']?> Ft</b></span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa-sharp fa-solid fa-sack-dollar fa-4x"></i><br/>
                    <b>ÖSSZES PROFIT</b><br/>
                    <span class="badge <?=($monthly['profit'] > 0) ? "bg-success text-white" : "bg-warning"?>"><b><?=$monthly['profit']?> Ft</b></span>
                </div>
            </div>
        </div>
    </div>
</div>