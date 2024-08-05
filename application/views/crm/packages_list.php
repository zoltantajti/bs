<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Csomaglista</h1>
</div>
<div class="container">
    <div class="row">
        <?php foreach($this->Orders->listPackages() as $package){ ?>
        <div class="col-md-3 col-xs-12">
            <div class="card <?=($package['receivedAt']) ? 'bg-success' : 'bg-warning'?>">
                <div class="card-body text-center">
                    <?=($package['receivedAt']) ? '<i class="fa-sharp fa-solid fa-cart-circle-check fa-3x"></i>' : '<i class="fa-sharp fa-solid fa-cart-circle-arrow-down fa-3x"></i>'?><br/>
                    <br/>
                    Csomag: <b><?=$package['packageID']?></b><br/>
                    Beküldve: <b><?=str_replace('-','.',$package['createdAt'])?></b><br/>
                    Átvéve: <b><?=($package['receivedAt']) ? str_replace('-','.',$package['receivedAt']) : ' - '?></b><br/>
                    <br/>
                    <a href="packages/<?=$package['packageID']?>" class="btn btn-outline-dark stretched-link">Részletek</a>
                </div>
            </div>
        </div>
        <?php }; ?>
    </div>
</div>