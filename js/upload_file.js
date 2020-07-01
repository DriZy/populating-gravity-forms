jQuery(function ($) {
    $(document).ready(function () {
        var mediaUploader;
        // $('#file_url_val').attr('disabled', 'disabled');
        $('#file_url').on('click', function (e) {
            e.preventDefault();
            mediaUploader = wp.media.frames.file_frame = wp.media({
                multiple: false,
                title: 'Upload File',
                button: {
                    text: 'Choose file',
                }
            });
            mediaUploader.on('select', function () {
                attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#file_url_val').val(attachment.url);
                $('.file_name').val(attachment.url);
            });
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
        });
    });
});
