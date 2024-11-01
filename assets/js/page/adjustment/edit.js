$(function () {
    "use strict";
    let options = {
        position: "top-right",
    };
    let notifier = new AWN(options);
    $(".js-example-basic-single").select2();
    /* To choose date */
    flatpickr("#date", {
        defaultDate: "today",
    });
    let currency = $("#currency").val();
    let warehouse_text = $("#warehouse_text").val();
    let search_route = $("#search_route").val();
    let variation_text = $("#variation_text").val();
    let image_path = $("#image_path").val();
    $("#product").autocomplete({
        source: function (request, response) {
            let wareHouseId = $("#warehouse").val();
            if (!wareHouseId) {
                notifier.alert(warehouse_text);
                $("#product").val("");
                return;
            }
            $.ajax({
                url: search_route,
                type: "GET",
                dataType: "json",
                data: {
                    search: request.term,
                    warehouse: wareHouseId,
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
                                                    <img src="${image_path}/${item.image}" alt="img">
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
                html += `<td>${ui.item.quantity_in_wirehouse}${ui.item.unit.name}</td>`;
                html += `<td><span class="stock_after_adjust">${ui.item.quantity}${ui.item.unit.name}</span></td>`;
                html += `<td><div class="input-group">
                                            <input type="number" class="form-control qty" name="qty[]" value="1"
                                                aria-label="Recipient's username" aria-describedby="basic-addon2">
                                            <span class="input-group-text" id="basic-addon2">${ui.item.unit.name}</span>
                                        </div></td>`;
                html += `<td><select name="type[]" class="form-select type"><option value="add">Add(+)</option><option value="subtract">Subtract(-)</option></select></td>`;
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
            notifier.alert(variation_text);
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
        html += `<td>${warehouse_quantity}${unit}</td>`;
        html += `<td><span class="stock_after_adjust">${total_quantity}${unit}</span></td>`;
        html += `<td><div class="input-group">
                                    <input type="number" class="form-control qty" name="qty[]" value="${quantity}"
                                        aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <span class="input-group-text" id="basic-addon2">${unit}</span>
                                </div></td>`;
        html += `<td><select name="type[]" class="form-select type"><option value="add">Add(+)</option><option value="subtract">Subtract(-)</option></select></td>`;
        html += `<td><button class="btn btn-danger btn-icon dltBtn"><i class="ri-delete-bin-2-line"></i></button></td>`;
        html += "</tr>";
        $("#tableBody").append(html);
        $("#productPopupModal").modal("hide");
        $("#product").val("");
    });

    //on change type and quantity
    $(document).on("keyup", ".qty", function () {
        updateAdjust($(this));
    });

    $(document).on("change", ".type", function () {
        updateAdjust($(this));
    });

    function updateAdjust(thisRow) {
        let row = thisRow.closest("tr");
        let stock = row.find("td").eq(1).text();
        let qty = row.find(".qty").val();
        let type = row.find(".type").val();
        let stockAfterAdjust = row.find(".stock_after_adjust");
        if (type === "add") {
            stockAfterAdjust.text(parseInt(stock) + parseInt(qty));
        } else {
            if (qty > stock) {
                notifier.alert("You cannot substract more than stock");
                row.find(".qty").val(1);
                stockAfterAdjust.text(parseInt(stock) - parseInt(1));
            } else {
                stockAfterAdjust.text(parseInt(stock) - parseInt(qty));
            }
        }
    }

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
