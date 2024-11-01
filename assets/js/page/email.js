$(function () {
            "use strict"
            let options = {
                'position': 'top-right'
            }
            let notifier = new AWN(options);
            $('.js-example-basic-single').select2();
            /* quill snow editor */
            let toolbarOptions = [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'font': [] }],
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                ['blockquote', 'code-block'],
                ['link', 'image', 'video', 'formula'],
                [{ 'header': 1 }, { 'header': 2 }],               // custom button values
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],      // superscript/subscript
                [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
                [{ 'direction': 'rtl' }],                         // text direction

                [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown

                [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                [{ 'align': [] }],

                ['image', 'video'],
                ['clean']                                         // remove formatting button
            ];
            let quillEditor = document.getElementById('quill-editor-area');
            let quill = new Quill('#note', {
                modules: {
                    toolbar: toolbarOptions,
                    clipboard: {
                        matchVisual: false
                    }
                },
                placeholder: 'Compose an epic...',
                preserveWhiteSpace: true,
                theme: 'snow',
            });
            quill.on('text-change', function () {
                quillEditor.value = quill.root.innerHTML;
            });


            $(document).on('click', '.short_code', function () {
                // insert in quill editor
                let range = quill.getSelection();
                let value = $(this).text().trim();
                quill.insertText(range.index, value);
            });

        })