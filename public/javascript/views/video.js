// Dropzone settings
Dropzone.options.videoupload = {
    paramName: "file",
    maxFilesize: 1000,
    maxFiles: 10,
    addRemoveLinks: true,
    dictRemoveFile: "Remove",
    dictCancelUpload: "Cancel",
    dictDefaultMessage: "Drop files here to upload",
    acceptedFiles: "video/*",
    parallelUploads: 10,
    uploadMultiple: true,
    autoProcessQueue: false,
	timeout: 600000,
    init: function () {
        var dropzoneInstance = this;
        var index = 1;
        
        this.on("addedfile", function(file){
            var videoElement = document.createElement('video');
            videoElement.src = URL.createObjectURL(file);
            var fileDuration = 0; // Set initial duration to 0

            videoElement.addEventListener('error', function() {
                // I use this just in case the user uploads an empty file
                // or a file that has the correct extension but not in the
                // correct format
                fileDuration = videoElement.duration || 0;
                
                if(index <= 10 && file.type.split('/')[0] == 'video' && file.size <= 1000000000) {
                    $('.dropzone').append('<input type="hidden" name="fileDuration[]" value="' +fileDuration+
                    '" uuid="' +file.upload.uuid+ '">');
                }
                
            });

            videoElement.addEventListener('loadedmetadata', function() {
                // getting the duration of the file
                fileDuration = videoElement.duration || 0;
                
                if(index <= 10 && file.type.split('/')[0] == 'video' && file.size <= 1000000000) {
                    $('.dropzone').append('<input type="hidden" name="fileDuration[]" value="' +fileDuration+
                    '" uuid="' +file.upload.uuid+ '">');
                }
            });
        });

        this.on("success", function (file) {
            location.reload();
        });

        this.on('addedfile', function (file) {
            if(index <= 10 && file.type.split('/')[0] == 'video' && file.size <= 1000000000) {
                $('#VideoUploadContainer').append('<input class="form-control mb-2 '+
                'upload-name" type="text" placeholder="File Name" value="' + file.name + '" uuid="'+file.upload.uuid+'">');
                $('#VideoUploadContainer').append('<textarea class="form-control note mb-4 upload-note"' + 
                ' type="text" placeholder="Description" uuid="'+ file.upload.uuid +'"></textarea>');
                index++;
              }
        });

        this.on('removedfile', function(file) {
            $('[uuid="'+file.upload.uuid+'"]').remove();
            index--;
        });

        this.on("processing", function() {
            this.options.autoProcessQueue = true;
        });
    }
};

// Javascript for popups

function escapeHTML(str) {
  return str.replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/&/g, "&amp;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#39;")
}

$(document).ready(function(){
	var dropzone = Dropzone.forElement("#videoupload");
	$(".upload-btn").click(function() {
        $('.upload-name').each(function() {
            var name = escapeHTML($(this).val());
            $("#videoupload").append("<input type='hidden' name='name[]' value='" +name+ "'>");
        });

        $('.upload-note').each(function() {
            var note = escapeHTML($(this).val());
            $("#videoupload").append("<input type='hidden' name='note[]' value='" +note+ "'>");
        });

		dropzone.processQueue();
	});
 });