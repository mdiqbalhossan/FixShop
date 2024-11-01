$(function () {
    "use strict";
    let product_type = $('input[name="product_type"]').val();
    let variation = $('input[name="variation"]').val();
    let default_currency = $('input[name="default_currency"]').val();

    $(".js-example-basic-single").select2();

    //generate barcode
    $("#generateBarcode").click(function () {
        let code = Math.floor(10000000 + Math.random() * 90000000);
        $("#code").val(code);
    });

    if (product_type == "variation") {
        let selectedValues = [];
        variation.forEach(function (variant) {
            selectedValues.push(variant);
        });
        let variation = $("#variation").val();
        let variationArr = variation.split("|");
        let variation_id = variationArr[0];
        let values = variationArr[1].split(",");
        let html = "";
        //selected field with variation value
        html += '<div class="mb-3">';
        html +=
            '<input type="hidden" name="variation_id" value="' +
            variation_id +
            '">';
        html +=
            '<label for="variation" class="form-label fs-14 text-dark">Variation Value <span class="text-danger">*</span></label>';
        html +=
            '<select class="js-example-basic-single" name="variation_value" id="variation_value" multiple>';
        values.forEach(function (value) {
            // Check if the value exists in the selectedValues array
            let selected = selectedValues.includes(value) ? "selected" : "";
            html +=
                '<option value="' +
                value +
                '" ' +
                selected +
                ">" +
                value +
                "</option>";
        });
        html += "</select>";
        html += "</div>";
        $("#variation_value").html(html);
        $(".js-example-basic-single").select2({
            placeholder: "Select Variation Value",
            allowClear: true,
        });
    }

    function append_html(values = null) {
        return `
                <input type="hidden" name="variation_value[]" value="${values}">
                <div class="col-md-4 ${values}" id="price_sec">
                            <div class="mb-3">
                                <label for="sku" class="form-label fs-14 text-dark">Price  ${
                                    values !== null
                                        ? `of ${values} variant`
                                        : ""
                                }
                <span class="text-danger">*</span>
            </label>
            <div class="input-group mb-3">
                <span class="input-group-text">${default_currency}</span>
                                    <input type="text" class="form-control" id="price" name="price[]"
                                           aria-label="Amount (to the nearest dollar)" placeholder="Price ">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 ${values}" id="sale_price_sec">
                            <div class="mb-3">
                                <label for="sku" class="form-label fs-14 text-dark">Sale Price ${
                                    values !== null
                                        ? `of ${values} variant`
                                        : ""
                                }
                <span class="text-danger">*</span>
            </label>
            <div class="input-group mb-3">
                <span class="input-group-text">${default_currency}</span>
                                    <input type="text" class="form-control" id="sale_price" name="sale_price[]"
                                           aria-label="Amount (to the nearest dollar)" placeholder="Sale Price ">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 ${values}" id="alert_quantity_sec">
                            <div class="mb-3">
                                <label for="alert_quantity"
                                       class="form-label fs-14 text-dark">Alert Quantity ${
                                           values !== null
                                               ? `of ${values} variant`
                                               : ""
                                       }
                <span class="text-danger">*</span>
                <i class="ri-information-fill fs-14" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Alert for a product shortage"></i>
                                </label>
                                <input type="text" class="form-control" id="alert_quantity" name="alert_quantity[]"
                                       placeholder="">
                            </div>
                        </div>`;
    }

    // Product Type Change
    $("#type").change(function () {
        let type = $(this).val();
        console.log(type);
        if (type === "single") {
            $(".auto_append_sec").html(append_html());
            $("#variation_sec").addClass("d-none");
            $("#variation_value").addClass("d-none");
        }
        if (type === "variation") {
            $("#variation_sec").removeClass("d-none");
            $("#variation_value").removeClass("d-none");
            $(".auto_append_sec").html("");
        }
    });

    //variation select
    $("#variation").change(function () {
        let variation = $(this).val();
        let variationArr = variation.split("|");
        let variation_id = variationArr[0];
        let values = variationArr[1].split(",");
        let html = "";
        //selected field with variation value
        html += '<div class="mb-3">';
        html +=
            '<input type="hidden" name="variation_id" value="' +
            variation_id +
            '">';
        html +=
            '<label for="variation" class="form-label fs-14 text-dark">Variation Value <span class="text-danger">*</span></label>';
        html +=
            '<select class="js-example-basic-single" name="variation_value" id="variation_value" multiple>';
        values.forEach(function (value) {
            html += '<option value="' + value + '">' + value + "</option>";
        });
        html += "</select>";
        html += "</div>";
        $("#variation_value").html(html);
        $(".js-example-basic-single").select2({
            placeholder: "Select Variation Value",
            allowClear: true,
        });
    });

    //Variation Value Change
    $("#variation_value").on("select2:select", function (e) {
        let variation_value = e.params.data.text;
        $(".auto_append_sec").append(append_html(variation_value));
    });

    //Variation Value Change
    $("#variation_value").on("select2:unselect", function (e) {
        let variation_value = e.params.data.text;
        let auto_append_sec = $(".auto_append_sec");
        auto_append_sec.find(`input[value="${variation_value}"]`).remove();
        auto_append_sec.find(`.${variation_value}`).remove();
    });

    //generate sku
    $("#generateSku").click(function () {
        let product_name = $("#name").val();
        let product_code = $("#code").val();
        let skuFormat = "S";
        if (product_name) {
            let words = product_name.split(" ");
            words.forEach((word) => {
                skuFormat += word[0];
            });
        } else {
            skuFormat += "DP-";
        }
        if (product_code) {
            skuFormat += "-" + product_code.substring(product_code.length - 3);
        } else {
            skuFormat += Math.floor(10000 + Math.random() * 90000);
        }
        $("#sku").val(skuFormat);
    });
});
