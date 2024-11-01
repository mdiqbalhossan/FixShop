$(function () {
    "use strict";
    $(".js-example-basic-single").select2();
    let options = {
        position: "top-right",
    };
    let notifier = new AWN(options);
    /* To choose date */
    flatpickr("#date");
    let price = $("input[name='return_amount']").val();
    let payableAmount = $("input[name='payable_amount']").val();
    let discount = $("input[name='return_discount']").val();
    let paidAmount = $("input[name='paid_amount']").val();
    let quantity_alert = $("input[name='quantity_alert']").val();
    //delete Table Row
    $(document).on("click", ".dltBtn", function () {
        let row = $(this).closest("tr");
        let dltPrice = row.find('input[type="number"]').eq(1).val();
        row.remove();
        price -= dltPrice;
        updatePayable();
    });

    //discount
    $(document).on("keyup", ".return_discount", function () {
        let getDiscount = $(this).val();
        discount = getDiscount;
        updatePayable();
    });

    //Paid Amount
    $(document).on("keyup", ".paid_amount", function () {
        let getPaidAmount = $(this).val();
        paidAmount = getPaidAmount;
        updatePayable();
    });

    //quantity
    $(document).on("input", ".quantity, .product_price", function () {
        let row = $(this).closest("tr");
        let quantity = parseInt(row.find('input[type="number"]').eq(0).val());
        let rowPrice = parseFloat(row.find('input[type="number"]').eq(1).val());
        let totalPrice = parseFloat(
            row.find('input[type="number"]').eq(2).val()
        );
        let sale_quantity = parseFloat(row.find(".sale_quantity").val());
        if (quantity > sale_quantity) {
            notifier.alert(quantity_alert);
            row.find('input[type="number"]').eq(0).val(sale_quantity);
        }
        let sumPrice = quantity * rowPrice;
        price -= sumPrice;
        row.find('input[type="number"]').eq(2).val(sumPrice);
        price += sumPrice;
        updatePayable();
    });

    //write payable amount and total amount price
    function updatePayable() {
        let totalSum = 0;
        $(".total").each(function () {
            let product_price = parseFloat($(this).val());
            if (!isNaN(price)) {
                totalSum += product_price;
            }
        });
        price = totalSum;
        $(".return_amount").val(price);
        let totalPrice = price - discount;
        $("#payable_amount").val(totalPrice);
        let dueAmount = totalPrice - paidAmount;
        $("#due_amount").val(dueAmount);
    }
});
