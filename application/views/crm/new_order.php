<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Új rendelés rögzítése</h1>
</div>
<div class="container">
    <form method="POST" action="" id="cartForm">
        <div class="row">
            <div class="col-md-12">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                    <input type="text" class="form-control is-invalid" list="customers" onKeyUp="checkCustomerName();" placeholder="Megrendelő neve" name="customerName" id="customerName" /> 
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-city"></i></span>
                    <input type="number" min="1000" max="9999" step="1" class="form-control" id="postCode" readonly />
                    <input type="text" class="form-control" id="city" readonly />
                    <input type="text" class="form-control" id="address" readonly />
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-phone"></i></span>
                    <input type="text" class="form-control" id="phone" readonly />
                </div>
            </div>
        </div>
        <div class="row"><div class="col-md-12"><b>Új termék hozzáadása a listához</b></div></div>
        <div class="row">
            <div class="col-md-2">
                <div class="input-group mb-3">
                    <input type="text" class="form-control is-invalid" list="products" onKeyUp="checkProductByCode();" placeholder="Termék kódja" id="productCode" />
                    <span class="input-group-text" id="productMarkt"><i class="fa-sharp fa-solid fa-magnifying-glass"></i></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Terméknév" id="productName" />
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Külső ár" id="productPrice" />
                    <span class="input-group-text">Ft</span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Beszerzési ár" id="productCost" />
                    <span class="input-group-text">Ft</span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Darabszám" id="productQty" />
                    <span class="input-group-text">db</span>
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" role="button" onClick="addProductToList();" class="btn btn-success"><i class="fa-sharp fa-solid fa-cart-plus"></i></button>
            </div>
        </div>
        <div class="row">
        <div class="col-md-6 hv-65-gray pt-1 pb-1 mb-1 bb-1-solid bb-dark-75">
                <b>Kódszám: Terméknév</b>
            </div>
            <div class="col-md-1 hv-65-gray pt-1 pb-1 mb-1 bb-1-solid bb-dark-75">
                <b>Egységár</b>
            </div>
            <div class="col-md-2 hv-65-gray pt-1 pb-1 mb-1 bb-1-solid bb-dark-75">
                <b>Darabszám kezelése</b>
            </div>
            <div class="col-md-1 hv-65-gray pt-1 pb-1 mb-1 bb-1-solid bb-dark-75 text-right">
               <b>Termék totál</b>
            </div>
            <div class="col-md-1 hv-65-gray pt-1 pb-1 mb-1 bb-1-solid bb-dark-75 text-right">
                <b>Totál profit / termék</b>
            </div>
            <div class="col-md-1 hv-65-gray pt-1 pb-1 mb-1 bb-1-solid bb-dark-75 text-right">
                
            </div>
        </div>
        <div class="row" id="productsOnCart"></div>
        <div class="row">
            <div class="col ms-auto" style="text-align:right;">
                Végösszeg: <b id="cartTotal">0</b><b> Ft</b><br/>
                Kiadás: <b id="costTotal">0</b><b> Ft</b><br/>
                Profit: <b id="profitTotal">0</b><b> Ft</b><br/>
            </div>
        </div>
        <div class="row">
            <div class="col ms-auto" style="text-align:right;">
                <button type="button" class="btn btn-outline-success" onClick="finishOrder();">Véglegesítés</button>
            </div>
        </div>
    </form>
</div>
<datalist id="customers"><?php foreach($this->db->select('name')->from('customers')->order_by('name','ASC')->where('sellerID',$this->Sess->get('id','user'))->get()->result_array() as $c){?><option><?=$c['name']?></option><?php }; ?></datalist>
<datalist id="products"><?php foreach($this->db->select('prodCode')->from('products')->order_by('prodCode','ASC')->where('sellerID',$this->Sess->get('id','user'))->get()->result_array() as $c){?><option><?=$c['prodCode']?></option><?php }; ?></datalist>
<div class="toast-container top-0 end-0 p-3">
<div id="Toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="toast-header">
    <strong class="me-auto" id="toastTitle">Megrendelés</strong>
    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
  <div class="toast-body" id="toastBody"></div>
</div>
</div>