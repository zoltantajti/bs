<a href="<?=site_url()?>admin" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <span class="fs-4 desktop">zAdmin</span>
    <span class="fs-4 mobile">Z</span>
</a>
<hr>
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="<?=site_url()?>" class="nav-link" aria-current="page">
            <i class="fa-solid fa-home"></i>
            <span class="nav-item-name">Kezdőlap</span>
        </a>
    </li>
    <?php if(@$sidebar){ ?>
    <li class="nav-item">
        <a href="<?=site_url('new_order')?>" class="nav-link" aria-current="page">
            <i class="fa-sharp fa-solid fa-cart-plus"></i>
            <span class="nav-item-name">Új rendelés</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?=site_url('order')?>" class="nav-link" aria-current="page">
            <i class="fa-sharp fa-solid fa-cart-circle-exclamation"></i>
            <span class="nav-item-name">Rendelések</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?=site_url('orders_summary')?>" class="nav-link" aria-current="page">
            <i class="fa-sharp fa-solid fa-cart-circle-exclamation"></i>
            <span class="nav-item-name">Rendelés összegző</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?=site_url('packages')?>" class="nav-link" aria-current="page">
            <i class="fa-sharp fa-thin fa-cart-shopping-fast"></i>
            <span class="nav-item-name">Várt csomagok</span>
        </a>
    </li>
    <?php }else{ ?>
    <li class="nav-item">
        <a href="<?=site_url('subscribe')?>" class="nav-link" aria-current="page">
            <i class="fa-thin fa-stars"></i>
            <span class="nav-item-name">Előfizetés</span>
        </a>
    </li>
    <?php }; ?>
</ul>
<?php if($this->User->getPermission() >= 2 && @$sidebar){ ?>
<hr>
<ul class="nav nav-pills flex-column">
    <li class="nav-item">
        <a href="<?=site_url('admin/sellers')?>" class="nav-link" aria-current="page">
            <i class="fa-sharp fa-regular fa-users"></i>
            <span class="nav-item-name">Eladói profilok</span>
        </a>
    </li>
</ul>
<?php }; ?>
<hr>
<div class="dropdown">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
        <span class="nav-item-name"><strong><?=$this->Sess->get('uname','user')?></strong></span>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
        <li><a class="dropdown-item" href="changePW">Jelszómódosítás</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="logout">Kilépés</a></li>
    </ul>
</div>