$(document).ready(function () {
    "use strict";

    let variant_route = $('input[name="variant_route"]').val();
    let stock_route = $('input[name="stock_route"]').val();
    $(".js-example-basic-single").select2();
    $("#variationModal").on("show.bs.modal", function (event) {
        let button = $(event.relatedTarget);
        let productId = button.data("value");
        $.ajax({
            url: variant_route,
            type: "GET",
            data: {
                productId: productId,
            },
            success: function (response) {
                $("#variationModal .modal-body").html(response);
            },
        });
    });

    // warehouse model
    $("#stockModal").on("show.bs.modal", function (event) {
        let button = $(event.relatedTarget);
        let productId = button.data("value");
        console.log(productId);
        $.ajax({
            url: stock_route,
            type: "GET",
            data: {
                productId: productId,
            },
            success: function (response) {
                $("#stockModal .modal-body").html(response);
            },
        });
    });
});
