$(function() {
            "use strict"
            let options = {
                'position': 'top-right'
            }
            let notifier = new AWN(options);
            let password_instruction = $('#password_instruction').val();
            let udpate_url = $('#udpate_url').val();
            let dlt_url = $('#dlt_url').val();
            let ban_confirmation = $('#ban_confirmation').val();
            let active_confirmation = $('#active_confirmation').val();
            //Add Event
            $("#addBtn").on('click', function() {
                $('#title').html('Add Staff');
                $('#submitBtn').html('Save Changes');
                $('#name').val('');
                $('#email').val('');
                $('#role').val('');
                $('#password').val('');
            })

            //Edit Event
            $(document).on('click', '.editBtn', function() {
                $('#passwordHelp').html(password_instruction);
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');
                let role = $(this).data('role');
                $("#title").html('Edit Staff')
                $("#name").val(name)
                $("#email").val(email)
                $("#role").val(role)
                $('#submitBtn').html('Update');
                let url = udpate_url;
                url = url.replace(':id', id);
                $('#form').attr('action', url);
                $('#method_sec').html('<input type="hidden" name="_method" value="PUT">')
            })

            // generate password
            $('#generate_password').on('click', function() {
                let password = Math.random().toString(36).slice(-8);
                $('#password').val(password);
            })

            //delete Event
            $(document).on('click', '.dltBtn', function() {
                let onOk = () => {
                let id = $(this).data('id');
                let url = dlt_url;
                url = url.replace(':id', id);
                $('#dltForm').attr('action', url);
                $('#dltForm').submit();
            };
            let onCancel = () => {
                    notifier.info('Your Item is safe')
                };
                let type = $(this).data('type');
                if (type === 'ban') {
                    notifier.confirm(ban_confirmation, onOk, onCancel)
                } else {
                    notifier.confirm(active_confirmation, onOk,
                        onCancel)
                }
            })
        })