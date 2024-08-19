$(document).ready(function(){
    $("#spinner").hide();
});

$("#cartForm").ready(() => {
    drawItems();
})

const checkCustomerName = () => {
    let customerName = $("#customerName").val();
    if(customerName.length >= 6)
    {
        ajax('POST','Api/getCustomerName',{name: customerName})
            .then((data) => {
                data = $.parseJSON(data);
                if(data.customer === "found"){
                    $("#customerMark").html('<i class="fa-sharp fa-solid fa-user-tie"></i>');
                    $("#customerName").removeClass('is-invalid').addClass('is-valid');
                    $("#postCode").val(data.data.postcode).prop('readonly',true);
                    $("#city").val(data.data.city).prop('readonly',true);
                    $("#address").val(data.data.address).prop('readonly',true);
                    $("#phone").val(data.data.phone).prop('readonly',true);
                }else{
                    $("#customerMark").html('<i class="fa-sharp fa-solid fa-user-plus"></i>');
                    $("#postCode").prop('readonly',false).removeClass('is-valid');
                    $("#city").prop('readonly',false).removeClass('is-valid');
                    $("#address").prop('readonly',false).removeClass('is-valid');
                    $("#phone").prop('readonly',false).removeClass('is-valid');
                };
            }, (error) => {console.error(error)});
    }else{
        $("#customerMark").html('<i class="fa-solid fa-block-question"></i>');
        $("#customerName").addClass('is-invalid').removeClass('is-valid');
    };
};
let fetchedProduct = null;
const checkProductByCode = () => {
    let productCode = $("#productCode").val();
    if(productCode.length >= 4){
        $("#spinner").show().center();
        ajax('POST','Api/getProductByCode',{code: productCode})
            .then((data) => {
                data = $.parseJSON(data);
                if(data.product === "found")
                {
                    fetchedProduct = data.details;
                    $("#productCode").removeClass('is-invalid').addClass('is-valid');
                    $("#productName").val(data.details.name);
                    $("#productPrice").val(data.details.price);
                    $("#productCost").val(data.details.cost);
                    $("#productQty").val(1);
                    $("#spinner").hide();
                }else if(data.product === "not-found"){
                    ajax('POST','BS/GetProduct/public',{code: productCode})
                    .then((data) => {
                        data = $.parseJSON(data);
                        $("#productName").val(data.name);
                        $("#productPrice").val(data.price);
                        $("#productQty").val(1);
                        $("#spinner").hide();
                    }).catch((err) => {console.error(err); $("#spinner").hide();});
                }
            })
    }else if(productCode.length == 0){
        $("#productCode").addClass('is-invalid').removeClass('is-valid');
        $("#productName").val('');
        $("#productPrice").val('');
        $("#productQty").val('');
        $("#spinner").hide();
    }else{
        $("#productCode").addClass('is-invalid').removeClass('is-valid');
    }
};
let productsOnCart = [];
const addProductToList = () => {
    if($("#productCode").val().length >= 4 && $("#productName").val().length >= 5 && $("#productPrice").val().length >= 3 && $("#productCost").val().length >= 3){
        let code = $("#productCode").val();
        let name = $("#productName").val();
        let price = $("#productPrice").val();
        let cost = $("#productCost").val();
        let qty = $("#productQty").val();        
        updateProductIfNeed(code,name,price,cost);
        let item = new Product(code,name,price,cost,qty);
        ajax('POST','Api/addToCart',{item: JSON.stringify(item)})
            .then((result) => {
                drawItems();
            });
        $("#productCode").val('');
        $("#productName").val('');
        $("#productPrice").val('');
        $("#productCost").val('');
        $("#productQty").val('');
        $("#productCode").addClass('is-invalid').removeClass('is-valid');
       
    }
}
const drawItems = () => {
    ajax('POST','Api/getItems').then((items) => {
        $("#productsOnCart").html('');
        let total = 0;
        let costTotal = 0;
        let profitTotal = 0;
        $.each($.parseJSON(items), function(i,item){
            let itemTotal = (item.price * item.qty);
            let itemCost = (item.options.Cost * item.qty);
            let profit = itemTotal - itemCost;
            let html = `
            <div class="col-md-6 hv-65-gray pt-1 pb-1 mb-1 bb-1-solid bb-dark-75">
                <b>${item.id}: ${item.name}</b>
            </div>
            <div class="col-md-1 hv-65-gray pt-1 pb-1 mb-1 bb-1-solid bb-dark-75">
                <b id="price">${formatNumber(item.price)}</b>
                <b> Ft</b>
            </div>
            <div class="col-md-2 hv-65-gray pt-1 pb-1 mb-1 bb-1-solid bb-dark-75">
                <div class="input-group">
                    <button class="btn btn-outline-secondary" onClick="minusOne('${item.rowid}');" type="button"><i class="fa-sharp fa-regular fa-square-minus"></i></button>
                    <input type="text" readonly class="form-control text-center" value="${item.qty}">
                    <button class="btn btn-outline-secondary" onClick="plusOne('${item.rowid}');" type="button"><i class="fa-sharp fa-regular fa-square-plus"></i></button>
                </div>
            </div>
            <div class="col-md-1 hv-65-gray pt-1 pb-1 mb-1 bb-1-solid bb-dark-75 text-right">
                <b id="itemTotal">${formatNumber(itemTotal)}</b>
                <b> Ft</b>
            </div>
            <div class="col-md-1 hv-65-gray pt-1 pb-1 mb-1 bb-1-solid bb-dark-75 text-right">
                <b id="profitPerItem">${formatNumber(profit)}</b>
                <b> Ft</b>
            </div>
            <div class="col-md-1 hv-65-gray pt-1 pb-1 mb-1 bb-1-solid bb-dark-75 text-right">
                <button class="btn btn-outline-danger" onClick="removeFromCart('${item.rowid}');" type="button"><i class="fa-sharp fa-solid fa-cart-minus"></i></button>
            </div>`;            
            total += itemTotal;
            costTotal += itemCost;
            profitTotal += profit;
            $("#productsOnCart").append(html);
        });
        $("#cartTotal").html(formatNumber(total));
        $("#costTotal").html(formatNumber(costTotal));
        $("#profitTotal").html(formatNumber(profitTotal));
    })
}

const finishOrder = () => {
    let cName = $("#customerName").val();
    let postCode = $("#postCode").val();
    let city = $("#city").val();
    let addr = $("#address").val();
    let phone = $("#phone").val();
    const toast = bootstrap.Toast.getOrCreateInstance($("#Toast"));
    if(cName.length > 6)
    {
        ajax("POST", "Api/finishOrder", {customer: cName, postCode: postCode, city: city, addr: addr, phone: phone}).then((data) => {
            if(data === "OK"){                
                $("#toastTitle").html('Sikeres rögzítés');
                $("#toastBody").html('A megrendelés sikeresen rögzítve!');
                toast.show();
                $("#customerName").val('').removeClass('is-valid').addClass('is-invalid');
                drawItems();
            };
        });
    }else{
        $("#toastTitle").html('HIBA');
        $("#toastBody").html('Kérlek válaszd ki a vevőt!');
        toast.show();
        $("#customerName").focus();
    }
};
const updateOrder = (id) => {
    const toast = bootstrap.Toast.getOrCreateInstance($("#Toast"));
    ajax("POST","Api/updateOrder", {id: id}).then((data) => {
        if(data === "OK"){
            $("#toastTitle").html('Sikeres módosítás');
            $("#toastBody").html('A megrendelés sikeresen módosítva!');
            toast.show();
            drawItems();
        }
    })
}

const submitOrder = () => {
    ajax("POST", "Api/submitOrder").then((data) => {
        if(data === "OK"){
            const toast = bootstrap.Toast.getOrCreateInstance($("#Toast"));
            $("#toastTitle").html('Sikeres művelet!');
            $("#toastBody").html('A rendelés leadását rögzítetted!');
            toast.show();
            setTimeout(() => location.reload(), 3000);
        }
    })
}
const confirmOrder = (id) => {
    ajax("POST", "Api/confirmOrder", {ID: id}).then((data) => {
        const toast = bootstrap.Toast.getOrCreateInstance($("#Toast"));
        $("#toastTitle").html('Sikeres művelet!');
        $("#toastBody").html('A rendelés átvételét rögzítetted!');
        toast.show();
        setTimeout(() => location.reload(), 3000);
    })
}
const setStatus = (target, id) => {
    ajax("POST", "Api/modifyOrderStatus", {id: id, target: target}).then((data) => {
        const toast = bootstrap.Toast.getOrCreateInstance($("#Toast"));
        $("#toastTitle").html('Sikeres művelet!');
        $("#toastBody").html('A rendelés státusza módosult!');
        toast.show();
        setTimeout(() => location.reload(), 3000);
    });
}
const setGroupStatus = (target, id) => {
    ajax("POST", "Api/setGroupStatus", {id: id, target: target}).then((data) => {
        const toast = bootstrap.Toast.getOrCreateInstance($("#Toast"));
        $("#toastTitle").html('Sikeres művelet!');
        $("#toastBody").html('A rendelések státusza módosult!');
        toast.show();
        setTimeout(() => location.reload(), 3000);
    })
}
let price = 0;
let discount = 0;
const calcPrice = () => {
    let val = $("#months").val();
    if(val > 0){
        ajax("POST", "Api/getPriceById", {id: val}).then((data) => {
            price = data;
            if(discount > 0){
                let _discount = price * (discount / 100);
                let _price = price - _discount;
                $("#price").val(_price);
            }else{
                $("#price").val(price);
            }
        });
    };
}
const checkCoupon = () => {
    let code = $("#coupon").val();
    if(code.length >= 6){
        ajax("POST","Api/checkCoupon",{code:code}).then((data)=>{
            data = $.parseJSON(data);
            if(data.success){
                discount = data.discount;
                $("#coupon").addClass("is-valid").removeClass("is-invalid");
                calcPrice();
            }else{
                if(data.msg){
                    const toast = bootstrap.Toast.getOrCreateInstance($("#Toast"));
                    $("#toastTitle").html('Hiba!');
                    $("#toastBody").html(data.msg);
                    toast.show();
                    $("#coupon").addClass("is-invalid").removeClass("is-valid");
                }
            }
        });
    }else{
        discount = 0;
        $("#coupon").removeClass("is-valid").removeClass("is-invalid");
        calcPrice();
    }
}

const plusOne = (rowid) => {ajax("POST","Api/updateItem",{'method': '+', 'rowid': rowid}).then(() => drawItems());}
const minusOne = (rowid) => {ajax("POST","Api/updateItem",{'method': '-', 'rowid': rowid}).then(() => drawItems());}
const removeFromCart = (rowid) => {ajax("POST","Api/removeItem",{'rowid': rowid}).then(() => drawItems());}
const updateProductIfNeed = (code,name,price,cost) => {if(fetchedProduct === null){ajax('POST','Api/updateProduct',{prodCode: code, name: name, price: price, cost: cost}).then((error) => console.error(error));};if(fetchedProduct !== null && (fetchedProduct.name !== name || fetchedProduct.price !== price || fetchedProduct.cost !== cost)){ajax('POST','Api/updateProduct',{prodCode: code, name: name, price: price, cost: cost});}}
const ajax = (type, url, data) => {return new Promise((resolve,reject) => {$.ajax({type: type,url: url,data: data,success: (data) => { resolve(data); },error: (error) => { reject(error); }});});}
const formatNumber = (number) => {return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");}
class Product{constructor(code,name,price,cost,qty){this.code = code;this.name = name;this.price = price;this.cost = cost;this.qty = qty;this.profit = (price - cost) * qty;}}
jQuery.fn.center = function () {this.css("position","absolute");this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + "px");this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + "px");return this;}
const filterCustomerTable = () => {let customer = $("#filterCustomer").val().toLowerCase();$("table tbody tr").each(function(){let _c = $(this).find("td:eq(2)").text().toLowerCase();(_c.indexOf(customer) > -1 || "" == customer) ? $(this).show(): $(this).hide()})}
$("#filterCustomer").keyup(function(){filterCustomerTable();});