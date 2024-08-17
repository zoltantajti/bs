<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?=$alist['title']?></h1>
</div>
<div class="container">
    <?php if($alist['hasnewbtn']){ ?>
    <div class="row">
        <div class="col-md-12">
            <?=$alist['newbtn']?>
        </div>
    </div>
    <?php }; ?>
    <div class="row">
        <div class="col-md-12">
            <?=$this->Msg->get()?>
            <?=$alist['html']?>
        </div>
    </div>
</div>
</div>