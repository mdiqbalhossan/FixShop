$(function() {
            "use strict"
            let options = {
                'position': 'top-right'
            }
            let notifier = new AWN(options);
            let update_url = $('#update_url').val();
            let delete_url = $('#delete_url').val();
            //Edit Event
            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let status = $(this).data('status');
                $("#title").html('Edit Units')
                $("#name").val(name)
                status === 1 ? $('#success-outlined').attr('checked', 'checked') : $('#danger-outlined')
                    .attr('checked', 'checked');

                let url = update_url;
                url = url.replace(':id', id);
                $('#unitForm').attr('action', url);
                $('#method_sec').html('<input type="hidden" name="_method" value="PUT">')
            })


            //delete Event
            $(document).on('click', '.dltBtn', function() {
                let onOk = () => {
                    let id = $(this).data('id');
                    let url = delete_url;
                    url = url.replace(':id', id);
                    $('#dltForm').attr('action', url);
                    $('#dltForm').submit();
                };
                let onCancel = () => {
                    notifier.info('Your Item is safe')
                };
                notifier.confirm('Are you sure you want to delete the items', onOk, onCancel)
            })
        })