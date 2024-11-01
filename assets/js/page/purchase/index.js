$(function () {
    "use strict";
    $(".js-example-basic-single").select2();
    flatpickr("#date", {
        mode: "range",
        dateFormat: "Y-m-d",
    });
    //Give Payment Modal Click
    $(document).on("click", ".givePayment", function () {
        let id = $(this).data("id");
        let invoice = $(this).data("invoice");
        let amount = $(this).data("amount");

        $("#invoice_no_modal").val(invoice);
        $("#payable_amount_modal").val(amount);
        $("#purchase_id_modal").val(id);
    });
});
