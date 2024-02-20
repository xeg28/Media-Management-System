// Dropzone settings 
var durations = {};
Dropzone.options.audioupload = {
    paramName: "file",
    maxFilesize: 100,
    maxFiles: 10,
    addRemoveLinks: true,
    dictRemoveFile: "Remove",
    dictCancelUpload: "Cancel",
    dictDefaultMessage: "Drop files here to upload",
    acceptedFiles: "audio/*, .webm",
    parallelUploads: 10,
    uploadMultiple: true,
    autoProcessQueue: false,
    timeout: 600000,
    init: function () {
        var dropzoneInstance = this;
        var index = 1;
        this.on("addedfile", function(file) {
            
            var audioElement = new Audio();
            audioElement.src = URL.createObjectURL(file);
            var fileDuration = 0; // Set initial duration to 0

            audioElement.addEventListener('error', function() {
                // I use this just in case the user uploads an empty file
                // or a file that has the correct extension but not in the
                // correct format
                fileDuration = audioElement.duration || 0;
                console.log("element error");
                if(index <= 10 && (file.type.split('/')[0] == 'audio' || file.type.split('/')[1] == 'webm') && file.size <= 100000000) { 
                    durations[file.upload.uuid] = fileDuration;
                }
            });

            audioElement.addEventListener('loadedmetadata', function() {
                // getting the duration of the file
                fileDuration = audioElement.duration || 0;
                if(index <= 10 && (file.type.split('/')[0] == 'audio' || file.type.split('/')[1] == 'webm') && file.size <= 100000000) {
                    durations[file.upload.uuid] = fileDuration;
                }
            });
        }); 


        this.on("success", function (file) {
            location.reload();
        });

        this.on('addedfile', function (file) {
            if(index <= 10 && (file.type.split('/')[0] == 'audio' || file.type.split('/')[1] == 'webm') && file.size <= 100000000) {
                $('#AudioUploadContainer').append('<input class="form-control mb-2 '+
                'upload-name" type="text" placeholder="File Name" value="' + file.name + '" uuid="'+file.upload.uuid+'">');
                $('#AudioUploadContainer').append('<textarea class="form-control note mb-4 upload-note"' + 
                ' type="text" placeholder="Description" uuid="'+ file.upload.uuid +'"></textarea>');
                $('.dropzone').append('<input type="hidden" name="uuid[]" value="' + file.upload.uuid +
                    '" uuid="' +file.upload.uuid+ '">');
                index++;
              }
        });

        this.on('removedfile', function(file) {
            $('[uuid="'+file.upload.uuid+'"]').remove();
            index--;
        });
			
		this.on('error', function(file, response) {
			$(file.previewElement).find('.dz-error-message').text(response);
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
	
    var dropzone = Dropzone.forElement("#audioupload");
	$(".upload-btn").click(function() {
        $('.dropzone').append("<input type='hidden' name='durations' value='" +JSON.stringify(durations)+ "'>");

        $('.upload-name').each(function() {
            var name = escapeHTML($(this).val());
            $("#audioupload").append("<input type='hidden' name='name[]' value='" +name+ "'>");
        });

        $('.upload-note').each(function() {
            var note = escapeHTML($(this).val());
            $("#audioupload").append("<input type='hidden' name='note[]' value='" +note+ "'>");
        });
		dropzone.processQueue();
	});
 });