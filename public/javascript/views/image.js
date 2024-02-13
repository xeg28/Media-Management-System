// Dropzone settings
Dropzone.options.imageupload = {
  paramName: "file",
  maxFilesize: 25,
  maxFiles: 10,
  addRemoveLinks: true,
  dictRemoveFile: "Remove",
  dictCancelUpload: "Cancel",
  dictDefaultMessage: "Drop files here to upload",
  acceptedFiles: "image/*",
  parallelUploads: 10,
  uploadMultiple: true,
  autoProcessQueue: false,
  timeout: 600000,
  init: function(){
    var index = 1;
    this.on("success", function (file) {
      location.reload();
    });

    this.on("addedfile", function (file) {
      if(index <= 10 && file.type.split('/')[0] == 'image' && file.size <= 25000000) {
        $('#ImageUploadContainer').append('<input class="form-control mb-2 '+
        'upload-name" type="text" placeholder="File Name" value="' + file.name + '" uuid="'+file.upload.uuid+'">');
        $('#ImageUploadContainer').append('<textarea class="form-control note mb-4 upload-note"' + 
        ' type="text" placeholder="Description" uuid="'+ file.upload.uuid +'"></textarea>');
        index++;
      }
    });

    this.on("removedfile", function(file) {
      $('[uuid="'+file.upload.uuid+'"]').remove();
      index--;
    });

    this.on("processing", function() {
      this.options.autoProcessQueue = true;
    });
  }
}


function escapeHTML(str) {
  return str.replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/&/g, "&amp;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#39;")
}


$(document).ready(function(){
    var dropzone = Dropzone.forElement("#imageupload");
    $(".upload-btn").click(function(){
      $('.upload-name').each(function() {
        var name = escapeHTML($(this).val());
        $('#imageupload').append('<input type="hidden" name="name[]" value="' + name + '">');
      });

      $('.upload-note').each(function() {
        var note = escapeHTML($(this).val());
        $('#imageupload').append('<input type="hidden" name="note[]" value="' + note + '">');
      });
      
      dropzone.processQueue();
    });
 });