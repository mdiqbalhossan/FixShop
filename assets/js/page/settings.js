$(function () {
            "use strict"
            let options = {
                'position': 'top-right'
            }
            let notifier = new AWN(options);
            let udpate_url = $('#udpate_url').val();
            let logo_url = $('#logo').val();
            let favicon_url = $('#favicon').val();
            let csrf_token = $('#csrf_token').val();
            $('.js-example-basic-single').select2();
            FilePond.registerPlugin(
                FilePondPluginImagePreview,
            );
            const logo = document.querySelector('.logo');
            const pond = FilePond.create(logo, {
                allowImagePreview: true,
                imagePreviewHeight: 170,
                styleLoadIndicatorPosition: 'center bottom',
                styleButtonRemoveItemPosition: 'center bottom',
                maxFiles: 1,
                maxFileSize: '3MB',
                server: {
                    process: {
                        url: udpate_url,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf_token
                        },
                        onload: (response) => {
                            $('#logo').val(response);
                        },
                        onerror: (response) => {
                            notifier.alert('Something went wrong');
                        }
                    }
                },
                files: [
                    {
                        source: logo_url,
                    }
                ]
            });

            const favicon = document.querySelector('.favicon');
            const faviconPond = FilePond.create(favicon, {
                allowImagePreview: true,
                imagePreviewHeight: 170,
                styleLoadIndicatorPosition: 'center bottom',
                styleButtonRemoveItemPosition: 'center bottom',
                maxFiles: 1,
                maxFileSize: '3MB',
                server: {
                    process: {
                        url: udpate_url,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf_token
                        },
                        onload: (response) => {
                            $('#favicon').val(response);
                        },
                        onerror: (response) => {
                            notifier.alert('Something went wrong');
                        }
                    }
                },
                files: [
                    {
                        source: favicon_url,
                    }
                ]
            });

        })