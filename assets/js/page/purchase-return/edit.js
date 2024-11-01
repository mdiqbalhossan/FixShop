$(function () {
            "use strict"
            $('.js-example-basic-single').select2();
            /* To choose date */
            flatpickr("#date");
            let price = $("#total_price").val();
            let payableAmount = $("#payable_amount").val();
            let discount = $("#discount").val();
            let receivedAmount = $("#paying_amount").val();
            let product_purchase = $("#purchase_product").val();
            let image_url = $("#image_url").val();

            $("#product").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: product_purchase,
                        type: 'GET',
                        dataType: "json",
                        data: {
                            search: request.term
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                create: function () {
                    $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
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
                    let html = '<tr>';
                    html += `<td><input type="text" class="form-control" value="${ui.item.label}" readonly></td>`
                    html += `<input type="hidden" name="product_id[]" class="form-control" value="${ui.item.id}" readonly>`
                    html += `<td><div class="input-group">
                                        <input type="number" class="form-control quantity" name="quantity[]" value="1"
                                            aria-label="Recipient's username" aria-describedby="basic-addon2">
                                        <span class="input-group-text" id="basic-addon2">${ui.item.unit.name}</span>
                                    </div></td>`;
                    html += `<td><div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">$</span>
                                        <input type="number" class="form-control product_price" name="price[]" value="${ui.item.price}"
                                            aria-label="Username" aria-describedby="basic-addon1">
                                    </div></td>`
                    html += `<td><div class="input-group">
                                        <span class="input-group-text" id="basic-addon2">$</span>
                                        <input type="number" class="form-control total" name="total[]" value="${ui.item.price}"
                                            aria-label="Recipient's username" aria-describedby="basic-addon2" readonly>
                                    </div></td>`
                    html += `<td><button class="btn btn-danger btn-icon dltBtn"><i class="ri-delete-bin-2-line"></i></button></td>`
                    html += '</tr>';
                    $('#tableBody').append(html);

                    price += parseFloat(ui.item.price);
                    updatePayable();
                    $('#product').val('');
                    return false;
                }
            });

            //delete Table Row
            $(document).on('click', '.dltBtn', function () {
                let row = $(this).closest('tr');
                let dltPrice = row.find('input[type="number"]').eq(1).val();
                row.remove();
                price -= dltPrice;
                updatePayable();
            });

            //discount
            $(document).on('keyup', '.return_discount', function () {
                let getDiscount = $(this).val();
                discount = getDiscount;
                updatePayable();
            })

            //Receiveable Amount
            $(document).on('keyup', '.received_amount', function () {
                let getReceivedAmount = $(this).val();
                receivedAmount = getReceivedAmount;
                updatePayable();
            });

            //quantity
            $(document).on('input', '.quantity, .product_price', function () {
                let row = $(this).closest('tr');
                let quantity = parseInt(row.find('input[type="number"]').eq(0).val());
                let rowPrice = parseFloat(row.find('input[type="number"]').eq(1).val());
                let totalPrice = parseFloat(row.find('input[type="number"]').eq(2).val());
                let sumPrice = quantity * rowPrice;
                price -= sumPrice;
                row.find('input[type="number"]').eq(2).val(sumPrice);
                price += sumPrice;
                updatePayable();
            })

            //write payable amount and total amount price
            function updatePayable() {
                let totalSum = 0;
                $('.total').each(function () {
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
        })