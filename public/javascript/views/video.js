// Dropzone settings
Dropzone.options.videoupload = {
    paramName: "file",
    maxFilesize: 1000,
    maxFiles: 1,
    addRemoveLinks: true,
    dictRemoveFile: "Remove",
    dictCancelUpload: "Cancel",
    dictDefaultMessage: "Drop files here to upload",
    acceptedFiles: "video/*",
    autoProcessQueue: false,
	timeout: 600000,
    init: function () {
        var dropzoneInstance = this;
        
        this.on("addedfile", function(file){
            var videoElement = document.createElement('video');
            videoElement.src = URL.createObjectURL(file);
            var fileDuration = 0; // Set initial duration to 0

            videoElement.addEventListener('error', function() {
                // I use this just in case the user uploads an empty file
                // or a file that has the correct extension but not in the
                // correct format
                fileDuration = videoElement.duration || 0;
                
                document.getElementById("fileDuration").value = fileDuration;
            });

            videoElement.addEventListener('loadedmetadata', function() {
                // getting the duration of the file
                fileDuration = videoElement.duration || 0;
                
                document.getElementById("fileDuration").value = fileDuration;
            });
        });

        this.on("success", function (file) {
            location.reload();
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
    $("#uservideos").click(function(){
		$(".shared-files").hide();
		$(".user-files").show();
	});
	
	$("#sharedvideos").click(function(){
		$(".user-files").hide();
		$(".shared-files").show();
	});
	
	var dropzone = Dropzone.forElement("#videoupload");
	$(".upload-btn").click(function() {
		var name = escapeHTML($("#uploadname").val());
		var note = escapeHTML($("#uploadnote").val());
		$("#videoupload").append("<input type='hidden' name='name' value='" +name+ "'>");
		$("#videoupload").append("<input type='hidden' name='note' value='" +note+ "'>");
		dropzone.processQueue();
	});
	
    // When the button is clicked, show the popup
	$(".add-share-input").click(function() {
		var index = $(".add-share-input").index($(this));
		var form = $('.share-form').eq(index);
		form.append('<input type="text" class="form-control mb-2 field" name="email[]" placeholder="Enter an email">');
	});
	
	$(".share-submit-btn").click(function() {
		var index = $(".share-submit-btn").index($(this));
		$('.share-form').eq(index).submit();
	});
	
	$(".share-btn").click(function(){
		var index = $(".share-btn").index($(this));
		$(".share-popup").eq(index).show();
	});

    $(".edit-btn").click(function(){
      var index = $(".edit-btn").index($(this));
       $(".edit-popup").eq(index).show();
    });

    // When the close button is clicked, hide the popup
    $(".close-popup").click(function(){
       $(this).closest(".edit-popup").hide();
       $(this).closest(".media-popup").hide();
       $(this).closest(".share-popup").hide();
    });

    document.addEventListener('click', function(event) {
      var target = event.target;

      if(target.classList.contains('close-popup')) {
          var index = target.getAttribute("index");
          document.getElementById("media"+index).pause();
      }

      if(target.classList.contains('show-media')) {
          var index = target.getAttribute('id');
          $('.media-popup').eq(index).show();
          document.getElementById("media"+index).play();
      }
    });
 });