$(function() {
            "use strict"
            let options = {
                'position': 'top-right'
            }
            let notifier = new AWN(options);
            let dltRoute = $('#dltRoute').val();
            $(document).on('click', '.dltBtn', function() {
                let onOk = () => {
                    let id = $(this).data('id');
                    let url = dltRoute;
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