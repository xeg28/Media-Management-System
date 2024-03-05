// Dropzone settings
var durations = {};
Dropzone.options.fileupload = {
  paramName: "file",
  maxFilesize: 1000,
  maxFiles: 10,
  addRemoveLinks: true,
  dictRemoveFile: "Remove",
  dictCancelUpload: "Cancel",
  dictDefaultMessage: "Drop files here to upload <br> <strong>or</strong> <br> Click to browse",
  acceptedFiles: "image/*, video/*, audio/*",
  parallelUploads: 10,
  uploadMultiple: true,
  autoProcessQueue: false,
  timeout: 600000,
  init: function(){
    var index = 1;
    this.on("success", function (file) {
      location.reload();
    });

    this.on("addedfile", function(file) { 
      if(file.type.split('/')[0] == 'image') return;
      var fileElement;
      if(file.type.split('/')[0] == 'video')
        fileElement = document.createElement('video');
      else if(file.type.split('/')[0] == 'audio')
        fileElement = document.createElement('audio');
      fileElement.src = URL.createObjectURL(file);

      var fileDuration = 0; 

      fileElement.addEventListener('error', function() {
          fileDuration = fileElement.duration || 0;
          if(index <= 10 && file.size <= 100000000) { 
            durations[file.upload.uuid] = fileDuration;
          }
      });

      fileElement.addEventListener('loadedmetadata', function() {
          // getting the duration of the file
          fileDuration = fileElement.duration || 0;
          if(index <= 10 && file.size <= 100000000) {
              durations[file.upload.uuid] = fileDuration;
          }
      });
    }); 

    this.on("addedfile", function (file) {
      if(index <= 10 && ['image', 'audio', 'video'].includes(file.type.split('/')[0]) && file.size <= 1000000000) {
        if($('.upload-btn').hasClass('hide')) $('.upload-btn').show();
        $('#UploadContainer').append('<div class="fileinput-wrapper" uuid="'+file.upload.uuid+'"><input class="form-control mb-2 '+
        'upload-name" type="text" placeholder="File Name" value="' + file.name + '" uuid="'+file.upload.uuid+'">'
        + '<textarea class="form-control note upload-note"' + 
        ' type="text" placeholder="Description" uuid="'+ file.upload.uuid +'"></textarea></div>');
        $('#UploadContainer').append();
        $('.dropzone').append('<input type="hidden" name="uuid[]" value="' + file.upload.uuid +
        '" uuid="' +file.upload.uuid+ '">');
        index++;
      }
    });

    this.on("removedfile", function(file) {
      $('[uuid="'+file.upload.uuid+'"]').remove();
      if(this.getQueuedFiles().length < 1) $('.upload-btn').hide();
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
    var dropzone = Dropzone.forElement("#fileupload");
    $(".upload-btn").click(function(){
    
      $('.dropzone').append("<input type='hidden' name='durations' value='" +JSON.stringify(durations)+ "'>");
      $('.upload-name').each(function() {
        var name = escapeHTML($(this).val());
        $('#fileupload').append('<input type="hidden" name="name[]" value="' + name + '">');
      });

      $('.upload-note').each(function() {
        var note = escapeHTML($(this).val());
        $('#fileupload').append('<input type="hidden" name="note[]" value="' + note + '">');
      });
      
      dropzone.processQueue();
    });
 });