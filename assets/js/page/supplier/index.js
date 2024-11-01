$(function() {
            "use strict"
            let options = {
                'position': 'top-right'
            }
            let notifier = new AWN(options);
            let add_supplier_text = $('#add_supplier_text').val();
            let submit_btn_text = $('#submit_btn_text').val();
            let edit_supplier = $('#edit_supplier').val();
            let update_url = $('#update_url').val();
            //Add Event
            $("#addBtn").on('click', function() {
                $('#title').html(add_supplier_text);
                $('#submitBtn').html(submit_btn_text);
                $('#name').val('');
                $('#address').val('');
            })

            //Edit Event
            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let address = $(this).data('address');
                let email = $(this).data('email');
                let phone = $(this).data('phone');
                let company = $(this).data('company');
                $("#title").html(edit_supplier)
                $("#name").val(name)
                $("#address").val(address)
                $("#email").val(email)
                $("#phone").val(phone)
                $("#company").val(company)

                $('#submitBtn').html('Update');
                let url = update_url;
                url = url.replace(':id', id);
                $('#form').attr('action', url);
                $('#method_sec').html('<input type="hidden" name="_method" value="PUT">')
            })
        })