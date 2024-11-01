$(function() {
            "use strict"
            let options = {
                'position': 'top-right'
            }
            let notifier = new AWN(options);
            let add_account = $("#add_account_text").val();
            let save_change = $("#save_change_text").val();
            let edit = $("#edit_text").val();
            let edit_url = $("#edit_url").val();
            //Add Event
            $("#addBtn").on('click', function() {
                $('#title').html(add_account);
                $('#submitBtn').html(save_change);
                $('#name').val('');
                $('#account_number').val('');
                $('#opening_balance').val('');
            })

            //Edit Event
            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let account_number = $(this).data('number');
                let opening_balance = $(this).data('balance');
                $("#title").html(edit)
                $("#name").val(name)
                $("#account_number").val(account_number)
                $("#opening_balance").val(opening_balance)
                $('#submitBtn').html('Update');
                let url = edit_url;
                url = url.replace(':id', id);
                $('#form').attr('action', url);
                $('#method_sec').html('<input type="hidden" name="_method" value="PUT">')
            })
        })