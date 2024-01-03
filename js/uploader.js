jQuery(document).ready(function($){
  var mediaUploaderbg;
  $('#mp_upload_bg_image_button').click(function(e) {
    e.preventDefault();
      if (mediaUploaderbg) {
        mediaUploaderbg.open();
        return;
    }
    mediaUploaderbg = wp.media.frames.file_frame = wp.media({
      title: 'یک تصویر انتخاب کنید',
      button: {
      text: 'یک تصویر انتخاب کنید'
    }, multiple: false });
    mediaUploaderbg.on('select', function() {
      var attachmentbg = mediaUploaderbg.state().get('selection').first().toJSON();
      $('#mp_bg_image').val(attachmentbg.url);
    });
    mediaUploaderbg.open();
  });
  var mediaUploader;
  $('#mp_upload_image_button').click(function(e) {
    e.preventDefault();
    if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'یک تصویر انتخاب کنید',
      button: {
      text: 'یک تصویر انتخاب کنید'
    }, multiple: false });
    mediaUploader.on('select', function() {
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('#mp_logo_image').val(attachment.url);
    });
    mediaUploader.open();
  });

  $(document).on("click", ".mwpl-upload-button", function (e) {
    e.preventDefault();
    let button = $(this),
        valueType = button.data('value-type') || 'id',
        dataType = button.data('type') || null,
        field = button.parent().find('input');

    let params = {
      title: 'Upload file',
      button: {
        text: 'Select file'
      },
      multiple: false
    };

    if (dataType){
      params['library'] = {
        type: dataType.split(',')
      };
    }
    let custom_uploader = wp.media(params).on('select', function () {
      let attachment = custom_uploader.state().get('selection').first().toJSON();
      if (valueType === 'id'){
        field.val(attachment.id).trigger('change');
      } else {
        field.val(attachment.url).trigger('change');
      }
    }).open();
  });
});