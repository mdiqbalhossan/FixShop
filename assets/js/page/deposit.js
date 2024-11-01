$(function() {
            "use strict"
            $('.js-example-basic-single').select2({
                dropdownParent: $('#modal')
            });
            /* To choose date */
            flatpickr("#date", {
                defaultDate: "today",
                static: true
            });
            let options = {
                'position': 'top-right'
            }
            let notifier = new AWN(options);
            let add_deposit = $('#add_deposit').val();
            let edit_deposit = $('#edit_deposit').val();
            let submit_deposit = $('#submit_deposit').val();
            let update_route = $('#update_route').val();
            //Add Event
            $("#addBtn").on('click', function() {
                $('#title').html(add_deposit);
                $('#submitBtn').html(submit_deposit);
                $('#account').val('');
                $('#amount').val('');
                $('#date').val('');
                $('#note').text('');
            })

            //Edit Event
            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                let account = $(this).data('account');
                let amount = $(this).data('amount');
                let date = $(this).data('date');
                let notes = $(this).data('notes');
                $("#title").html(edit_deposit)
                //select account id and set it to select option
                $("#account").val(account).trigger('change')
                $("#amount").val(amount)
                $("#date").val(date)
                $("#note").val(notes)
                $('#submitBtn').html('Update');
                let url = update_route;
                url = url.replace(':id', id);
                $('#form').attr('action', url);
                $('#method_sec').html('<input type="hidden" name="_method" value="PUT">')
            })
        })