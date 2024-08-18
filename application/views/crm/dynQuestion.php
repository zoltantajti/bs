<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kérdés</h1>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?=$q?>
            <form method="post" action="">
                <?=$this->Msg->get()?>
                <div class="form-group mb-3">
                    <input type="checkbox" id="yes" name="yes" value="1"/>
                    <label for="yes">Igen, valóban végre szeretném hajtani!</label>
                </div>
                <div class="btn-group" role="group">
                    <button type="submit" name="submit" value="1" class="btn btn-outline-danger">Mehet!</button>
                    <a href="<?=$link?>" class="btn btn-outline-success">Vissza</a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>