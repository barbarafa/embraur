import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

function initEditors() {
    document.querySelectorAll('textarea.js-ckeditor').forEach((el) => {
        ClassicEditor.create(el, {
            simpleUpload: {
                uploadUrl: '/uploads/ckeditor',
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                }
            },
            mediaEmbed: { previewsInData: true }
        }).catch(console.error);
    });
}

document.addEventListener('DOMContentLoaded', initEditors);
