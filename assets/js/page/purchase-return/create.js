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
    let receivedAmount = 0;

    //discount
    $(document).on("keyup", ".discount", function () {
        let getDiscount = $(this).val();
        discount = getDiscount;
        updatePayable();
    });

    //Receiveable Amount
    $(document).on("keyup", ".received_amount", function () {
        let getReceivedAmount = $(this).val();
        receivedAmount = getReceivedAmount;
        updatePayable();
    });

    //quantity
    $(document).on("input", ".quantity", function () {
        let row = $(this).closest("tr");
        let quantity = parseInt(row.find('input[type="number"]').eq(2).val());
        let rowPrice = parseFloat(row.find('input[type="number"]').eq(3).val());
        let totalPrice = parseFloat(
            row.find('input[type="number"]').eq(4).val()
        );
        let sumPrice = quantity * rowPrice;
        price -= sumPrice;
        row.find('input[type="number"]').eq(4).val(sumPrice);
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
        payableAmount = price - discount;
        $("#receivable_amount").val(payableAmount);
        let dueAmount = payableAmount - receivedAmount;
        $("#due_amount").val(dueAmount);
    }
});
