$(function () {
    "use strict";
    $(".js-example-basic-single").select2();
    /* To choose date */
    flatpickr("#date", {
        defaultDate: "today",
    });
    let price = 0;
    let payableAmount = 0;
    let discount = 0;
    let paidAmount = 0;

    //discount
    $(document).on("keyup", ".discount", function () {
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
    $(document).on("input", ".quantity", function () {
        let row = $(this).closest("tr");
        let quantity = parseInt(row.find('input[type="number"]').eq(1).val());
        let rowPrice = parseFloat(row.find('input[type="number"]').eq(2).val());
        let totalPrice = parseFloat(
            row.find('input[type="number"]').eq(3).val()
        );
        let sumPrice = quantity * rowPrice;
        console.log(quantity);
        price -= sumPrice;
        row.find('input[type="number"]').eq(3).val(sumPrice);
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
        $("#total_amount").val(price);
        let totalPrice = price - discount;
        $("#payable_amount").val(totalPrice);
        let dueAmount = totalPrice - paidAmount;
        $("#due_amount").val(dueAmount);
    }
});
