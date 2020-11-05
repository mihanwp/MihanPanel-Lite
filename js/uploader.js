jQuery(document).ready(function($){

  var mediaUploaderbg;
  $('#mp_upload_bg_image_button').click(function(e) {
    e.preventDefault();
      if (mediaUploaderbg) {
      mediaUploaderbg2.open();
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
});