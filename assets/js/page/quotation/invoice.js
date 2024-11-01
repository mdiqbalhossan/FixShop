$(function () {
            "use strict"
            // print function to print the invoice
            $('.btn_print').on('click', function () {
                let printContents = $('.card-invoice').html();
                let originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
            });

            /**
             * if print window close then reload the page
             */
            window.onafterprint = function () {
                location.reload();
            }
        })