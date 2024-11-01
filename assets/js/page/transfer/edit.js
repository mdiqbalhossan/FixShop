$(function () {
    "use strict";
    $(".js-example-basic-single").select2();
    /* To choose date */
    flatpickr("#date", {
        defaultDate: "today",
    });
    let options = {
        position: "top-right",
    };
    let notifier = new AWN(options);
    let currency = $("input[name='default_currency']").val();
    let select_warehouse = $("input[name='select_warehouse']").val();
    let quantity_alert = $("input[name='quantity_alert']").val();
    let purchase_product = $("input[name='purchase_product']").val();
    let image_url = $("input[name='image_url']").val();
    let select_variation = $("input[name='select_variation']").val();

    $("#product").autocomplete({
        source: function (request, response) {
            let wirehouse = $("#from_warehouse").val();
            if (wirehouse == null) {
                Swal.fire({
                    icon: "warning",
                    title: "Oops...",
                    text: select_warehouse,
                });
                return false;
            }
            $.ajax({
                url: purchase_product,
                type: "GET",
                dataType: "json",
                data: {
                    search: request.term,
                    warehouse: wirehouse,
                },
                success: function (data) {
                    response(data);
                },
            });
        },
        create: function () {
            $(this).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                    .addClass("list-group-item")
                    .append(
                        `<div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm">
                                                    <img src="${image_url}/${item.image}" alt="img">
                                                </span>
                                                <div class="ms-2 fw-semibold">
                                                    ${item.label} (${item.code})
                                                </div>
                                            </div>`
                    )
                    .appendTo(ul);
            };
        },
        select: function (event, ui) {
            if (ui.item.product_type == "variation") {
                $("#productPopupModal").modal("show");
                $("#product_name").val(ui.item.label);
                $("#variation_name").val(ui.item.variants[0].name);
                $("#product_id").val(ui.item.id);
                $("#stock_quantity").val(ui.item.quantity);
                $("#variation_id").val(ui.item.variants[0].id);
                $("#current_stock").val(0);
                let values = ui.item.variants;
                let html = "";
                values.forEach(function (value) {
                    html += `<input type="radio" class="btn-check variation_value_modal" name="variation_value_modal" value="${value.pivot.value}|${currency}${value.pivot.sale_price}|${value.pivot.current_stock}|${ui.item.unit.name}" id="variation_value_modal_${value.pivot.value}">
                            <label class="btn btn-outline-primary m-1" for="variation_value_modal_${value.pivot.value}">${value.pivot.value} (${currency}${value.pivot.sale_price})</label>`;
                });
                $("#variation_value_data").html(html);
            } else {
                let html = "<tr>";
                html += `<td><input type="text" class="form-control" value="${ui.item.label}" readonly></td>`;
                html += `<input type="hidden" name="product_id[]" class="form-control" value="${ui.item.id}" readonly>`;
                html += `<input type="hidden" name="variation_id[]" class="form-control" value="" readonly>`;
                html += `<input type="hidden" name="variation_value[]" class="form-control" value="" readonly>`;
                html += `<input type="hidden" name="stock_quantity[]" class="form-control stock_quantity" value="${ui.item.quantity_in_wirehouse}" readonly>`;
                html += `<td>${ui.item.quantity_in_wirehouse} ${ui.item.unit.name}</td>`;
                html += `<td><div class="input-group">
                                            <input type="number" class="form-control qty" name="qty[]" value="1"
                                                aria-label="Recipient's username" aria-describedby="basic-addon2">
                                            <span class="input-group-text" id="basic-addon2">${ui.item.unit.name}</span>
                                        </div></td>`;
                html += `<td><button class="btn btn-danger btn-icon dltBtn"><i class="ri-delete-bin-2-line"></i></button></td>`;
                html += "</tr>";
                $("#tableBody").append(html);
                $("#product").val("");
                return false;
            }
        },
    });

    // Insert Model Data
    $(document).on("click", "#insert_modal_data", function () {
        let product_id = $('input[name="product_id"]').val();
        let variation_id = $('input[name="variation_id"]').val();
        let quantity = $('input[name="quantity"]').val();
        let product_name = $("#product_name").val();
        let variation_name = $("#variation_name").val();
        let variation_value = $(
            'input[name="variation_value_modal"]:checked'
        ).val();
        if (!variation_value) {
            notifier.alert(select_variation);
            return;
        }
        let split = variation_value.split("|");
        let price = split[1];
        let warehouse_quantity = split[2];
        let unit = split[3];
        price = parseFloat(price.replace(/[^0-9.-]+/g, ""));
        variation_value = split[0];
        let total = price * quantity;
        let total_quantity = parseInt(warehouse_quantity) + parseInt(quantity);
        let html = "<tr>";
        html += `<td><input type="text" class="form-control" value="${product_name} (${variation_value})" readonly></td>`;
        html += `<input type="hidden" name="product_id[]" class="form-control" value="${product_id}" readonly>`;
        html += `<input type="hidden" name="variation_id[]" class="form-control" value="${variation_id}" readonly>`;
        html += `<input type="hidden" name="variation_value[]" class="form-control" value="${variation_value}" readonly>`;
        html += `<input type="hidden" name="stock_quantity[]" class="form-control stock_quantity" value="${warehouse_quantity}" readonly>`;
        html += `<td>${warehouse_quantity} ${unit}</td>`;
        html += `<td><div class="input-group">
                                    <input type="number" class="form-control qty" name="qty[]" value="${quantity}"
                                        aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <span class="input-group-text" id="basic-addon2">${unit}</span>
                                </div></td>`;
        html += `<td><button class="btn btn-danger btn-icon dltBtn"><i class="ri-delete-bin-2-line"></i></button></td>`;
        html += "</tr>";
        $("#tableBody").append(html);
        $("#productPopupModal").modal("hide");
        $("#product").val("");
    });

    $(document).on("keyup", ".qty", function () {
        let stock = $(this).closest("tr").find(".stock_quantity").val();
        console.log(stock);
        let qty = $(this).val();
        if (parseInt(qty) > parseInt(stock)) {
            Swal.fire({
                icon: "warning",
                title: "Oops...",
                text: quantity_alert,
            });
            $(this).val(1);
        }
    });
    //delete Table Row
    $(document).on("click", ".dltBtn", function () {
        let row = $(this).closest("tr");
        let dltPrice = row.find('input[type="number"]').eq(1).val();
        row.remove();
    });

    //Select Variation Value
    $(document).on("change", ".variation_value_modal", function () {
        let split = $(this).val().split("|");
        let quantity = split[2];
        $("#current_stock").val(quantity);
    });
});
