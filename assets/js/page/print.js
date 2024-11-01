$(function () {
    "use strict";
    let options = {
        position: "top-right",
    };
    let notifier = new AWN(options);
    $(".js-example-basic-single").select2();

    let currency = $("#defaultCurrency").val();
    let selectWarehouse = $("#select_warehouse").val();
    let product_route = $("#product_route").val();
    let image_path = $("#image_path").val();
    let select_variation = $("#select_variation").val();
    let select_product = $("#select_product").val();
    let print_labels = $("#print_labels").val();

    $("#product").autocomplete({
        source: function (request, response) {
            let wareHouse = $("#warehouse").val();
            if (wareHouse === null) {
                notifier.alert(selectWarehouse);
                $("#product").val("");
                return;
            }
            $.ajax({
                url: product_route,
                type: "GET",
                dataType: "json",
                data: {
                    search: request.term,
                    warehouse: wareHouse,
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
                $("#code").val(ui.item.code);
                $("#variation_name").val(ui.item.variants[0].name);
                $("#product_id").val(ui.item.id);
                $("#stock_quantity").val(ui.item.quantity);
                $("#variation_id").val(ui.item.variants[0].id);
                let values = ui.item.variants;
                let html = "";
                values.forEach(function (value) {
                    html += `<input type="radio" class="btn-check" name="variation_value_modal" value="${value.pivot.value}|${currency}${value.pivot.sale_price}" id="variation_value_modal_${value.pivot.value}">
                            <label class="btn btn-outline-primary m-1" for="variation_value_modal_${value.pivot.value}">${value.pivot.value} (${currency}${value.pivot.sale_price})</label>`;
                });
                $("#variation_value_data").html(html);
            } else {
                let html = '<tr class="rowCount">';
                html += `<td>${ui.item.label}</td>`;
                html += `<input type="hidden" name="product_id" class="form-control" value="${ui.item.id}" readonly>`;
                html += `<td>${ui.item.code}</td>`;
                html += `<td>${ui.item.price}</td>`;
                html += `<td><div class="input-group">
                                        <input type="number" class="form-control quantity" name="quantity" value="1"
                                            aria-label="Recipient's username" aria-describedby="basic-addon2">
                                        <span class="input-group-text" id="basic-addon2">piece</span>
                                    </div></td>`;
                html += "</tr>";
                $("#tableBody").html(html);
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
        let code = $("#code").val();
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
        price = parseFloat(price.replace(/[^0-9.-]+/g, ""));
        variation_value = split[0];
        let total = price * quantity;
        let html = '<tr class="rowCount">';
        html += `<td>${product_name} (${variation_value})</td>`;
        html += `<input type="hidden" name="product_id" class="form-control" value="${product_id}" readonly>`;
        html += `<input type="hidden" name="variation_id" class="form-control" value="${variation_id}" readonly>`;
        html += `<input type="hidden" name="variation_value" class="form-control" value="${variation_value}" readonly>`;
        html += `<td>${code}</td>`;
        html += `<td>${price}</td>`;
        html += `<td><div class="input-group">
                            <input type="number" class="form-control quantity" name="quantity" value="${quantity}"
                                aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2">piece</span>
                        </div></td>`;
        html += "</tr>";
        $("#tableBody").html(html);
        $("#productPopupModal").modal("hide");
        $("#product").val("");
    });

    $(document).on("change", "#paper_size", function () {
        let rowCount = $(".rowCount").length;
        if (rowCount === 0) {
            notifier.alert(select_product);
            return;
        }
        printLabel();
    });

    $(document).on("click", ".rfrsBtn", function () {
        let rowCount = $(".rowCount").length;
        if (rowCount === 0) {
            notifier.alert(select_product);
            return;
        }
        printLabel();
    });

    function printLabel() {
        let paper_size = $("#paper_size").val();
        let quantity = $(".quantity").val();
        let product_id = $('input[name="product_id"]').val();
        $.ajax({
            url: print_labels,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                paper_size: paper_size,
                quantity: quantity,
                product_id: product_id,
            },
            success: function (data) {
                let barcode_sheet = $(".barcode");
                barcode_sheet.html(data);
            },
        });
    }

    $(document).on("click", ".resetBtn", function () {
        $("#tableBody").html("");
        $("#product").val("");
        $("#paper_size").val("");
        $(".barcode").html("");
    });

    $(document).on("click", ".printBtn", function () {
        let rowCount = $(".rowCount").length;
        if (rowCount === 0) {
            notifier.alert(select_product);
            return;
        }
        let section = document.getElementById("barcode-sheet");
        let windowObject = window.open(
            "",
            "PrintWindow",
            "width=750,height=650,top=50,left=50"
        );
        windowObject.document.write(section.innerHTML);
        windowObject.document.close();
        windowObject.focus();
        windowObject.print();
    });
});
