$(function () {
    "use strict";

    let categoryStore = $('input[name="category_store"]').val();
    let brandStore = $('input[name="brand_store"]').val();
    let unitStore = $('input[name="unit_store"]').val();
    let default_currency = $('input[name="default_currency"]').val();

    $(".js-example-basic-single").select2();

    $("#uploadButton").click(function () {
        // Trigger a click event on the hidden input field
        $("#uploadInput").trigger("click");
    });

    //generate barcode
    $("#generateBarcode").click(function () {
        let code = Math.floor(10000000 + Math.random() * 90000000);
        $("#code").val(code);
    });

    // When a file is selected using the hidden input field
    $("#uploadInput").change(function () {
        let input = this;

        // Check if any file is selected
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.readAsDataURL(input.files[0]);
            reader.onload = function (e) {
                $("#previewImage").attr("src", e.target.result);
            };
        }
    });

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

    //Add Category
    $(document).on("click", "#add_category", function () {
        let name = $("#category_name").val();
        let url = categoryStore;
        let modal = "#addCategoryModal";
        let id = "#category";
        addData({ name, url, modal, id });
    });

    //Add Brand
    $(document).on("click", "#add_brand", function () {
        let name = $("#brand_name").val();
        let url = brandStore;
        let modal = "#addBrandModal";
        let id = "#brand";
        addData({ name, url, modal, id });
    });

    //Add Unit
    $(document).on("click", "#add_unit", function () {
        let name = $("#unit_name").val();
        let url = unitStore;
        let modal = "#addUnitModal";
        let id = "#unit";
        addData({ name, url, modal, id });
    });

    function addData(data) {
        let name = data.name;
        let url = data.url;
        let modal = data.modal;
        let id = data.id;
        let status = 1;
        $.ajax({
            url: url,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                name: name,
                status: status,
            },
            success: function (response) {
                $(modal).modal("hide");
                $(id).append(
                    `<option value="${response.id}">${response.name}</option>`
                );
                $(id).val(response.id).trigger("change");
            },
        });
    }
});
