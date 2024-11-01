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
    let select_warehouse = $("#select_warehouse").val();
    let purchase_product = $("#purchase_product").val();
    let image_url = $("#image_url").val();
    let select_variation = $("#select_variation").val();
    let supplier_store = $("#supplier_store").val();
    let currency = $("#default_currency").val();
    let price = 0;
    let payableAmount = 0;
    let tax = 0;
    let discount = 0;
    let paidAmount = 0;
    $("#product").autocomplete({
        source: function (request, response) {
            let wareHouseId = $("#warehouse").val();
            if (!wareHouseId) {
                notifier.alert(select_warehouse);
                $("#product").val("");
                return;
            }
            $.ajax({
                url: purchase_product,
                type: "GET",
                dataType: "json",
                data: {
                    search: request.term,
                    warehouse: wareHouseId,
                },
                success: function (data) {
                    console.log(data);
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
                    html += `<input type="radio" class="btn-check variation_value_modal" name="variation_value_modal" value="${value.pivot.value}|${currency}${value.pivot.sale_price}|${ui.item.unit.name}|${value.pivot.current_stock}" id="variation_value_modal_${value.pivot.value}">
                            <label class="btn btn-outline-primary m-1" for="variation_value_modal_${value.pivot.value}">${value.pivot.value} (${currency}${value.pivot.sale_price})</label>`;
                });
                $("#variation_value_data").html(html);
            } else {
                let html = "<tr>";
                html += `<td><input type="text" class="form-control" value="${ui.item.label}" readonly></td>`;
                html += `<input type="hidden" name="product_id[]" class="form-control" value="${ui.item.id}" readonly>`;
                html += `<input type="hidden" name="variation_id[]" class="form-control" value="" readonly>`;
                html += `<input type="hidden" name="variation_value[]" class="form-control" value="" readonly>`;
                html += `<td><div class="input-group">
                                        <input type="number" class="form-control quantity" name="quantity[]" value="1"
                                            aria-label="Recipient's username" aria-describedby="basic-addon2">
                                        <span class="input-group-text" id="basic-addon2">${ui.item.unit.name}</span>
                                    </div></td>`;
                html += `<td><div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">$</span>
                                        <input type="number" class="form-control product_price" name="price[]" value="${ui.item.price}"
                                            aria-label="Username" aria-describedby="basic-addon1">
                                    </div></td>`;
                html += `<td><div class="input-group">
                                        <span class="input-group-text" id="basic-addon2">$</span>
                                        <input type="number" class="form-control total" name="total[]" value="${ui.item.price}"
                                            aria-label="Recipient's username" aria-describedby="basic-addon2" readonly>
                                    </div></td>`;
                html += `<td><button class="btn btn-danger btn-icon dltBtn"><i class="ri-delete-bin-2-line"></i></button></td>`;
                html += "</tr>";
                $("#tableBody").append(html);

                price += parseFloat(ui.item.price);
                updatePayable();
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
        let unit = split[2];
        price = parseFloat(price.replace(/[^0-9.-]+/g, ""));
        variation_value = split[0];
        let total = price * quantity;
        let html = "<tr>";
        html += `<td><input type="text" class="form-control" value="${product_name} (${variation_value})" readonly></td>`;
        html += `<input type="hidden" name="product_id[]" class="form-control" value="${product_id}" readonly>`;
        html += `<input type="hidden" name="variation_id[]" class="form-control" value="${variation_id}" readonly>`;
        html += `<input type="hidden" name="variation_value[]" class="form-control" value="${variation_value}" readonly>`;
        html += `<td><div class="input-group">
                            <input type="number" class="form-control quantity" name="quantity[]" value="${quantity}"
                                aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2">${unit}</span>
                        </div></td>`;
        html += `<td><div class="input-group">
                            <span class="input-group-text" id="basic-addon1">$</span>
                            <input type="number" class="form-control product_price" name="price[]" value="${price}"
                                aria-label="Username" aria-describedby="basic-addon1">
                        </div></td>`;
        html += `<td><div class="input-group">
                            <span class="input-group-text" id="basic-addon2">$</span>
                            <input type="number" class="form-control total" name="total[]" value="${total}"
                                aria-label="Recipient's username" aria-describedby="basic-addon2" readonly>
                        </div></td>`;
        html += `<td><button class="btn btn-danger btn-icon dltBtn"><i class="ri-delete-bin-2-line"></i></button></td>`;
        html += "</tr>";
        $("#tableBody").append(html);
        $("#productPopupModal").modal("hide");
        updatePayable();
        $("#product").val("");
    });

    //Delete Table Row
    $(document).on("click", ".dltBtn", function () {
        let row = $(this).closest("tr");
        let dltPrice = row.find('input[type="number"]').eq(1).val();
        row.remove();
        price -= dltPrice;
        updatePayable();
    });

    //discount
    $(document).on("keyup", ".discount", function () {
        let getDiscount = $(this).val();
        discount = getDiscount;
        updatePayable();
    });

    //Tax
    $(document).on("keyup", ".tax", function () {
        let getTax = $(this).val();
        let taxAmount = (price * getTax) / 100;
        $(".tax_amount").text(taxAmount);
        tax = taxAmount;
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
        let sumPrice = quantity * rowPrice;
        price -= sumPrice;
        row.find('input[type="number"]').eq(2).val(sumPrice);
        price += sumPrice;
        updatePayable();
    });

    //Paid Amount
    $(document).on("keyup", ".paid_amount", function () {
        let getPaidAmount = $(this).val();
        paidAmount = getPaidAmount;
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
        $("#total_price").val(price);
        let totalPrice = price + tax - discount;
        $("#payable_amount").val(totalPrice);
        let dueAmount = totalPrice - paidAmount;
        $("#due_amount").val(dueAmount);
    }

    //Add Supplier
    $(document).on("click", "#add_supplier", function () {
        let name = $("#name").val();
        let email = $("#email").val();
        let phone = $("#phone").val();
        let company = $("#company").val();
        let address = $("#address").val();
        $.ajax({
            url: supplier_store,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                name: name,
                email: email,
                phone: phone,
                company: company,
                address: address,
            },
            success: function (data) {
                $("#addSupplierModal").modal("hide");
                $("#supplier").append(
                    `<option value="${data.id}">${data.name}</option>`
                );
                $("#supplier").val(data.id).trigger("change");
            },
        });
    });

    // variation_value_modal click
    $(document).on("click", ".variation_value_modal", function () {
        let split = $(this).val().split("|");
        let quantity = split[3];
        $("#current_stock").val(quantity);
    });
});
