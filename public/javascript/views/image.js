// Dropzone settings
Dropzone.options.imageupload = {
    paramName: "file",
    maxFilesize: 25,
    maxFiles: 1,
    addRemoveLinks: true,
    dictRemoveFile: "Remove",
    dictCancelUpload: "Cancel",
    dictDefaultMessage: "Drop files here to upload",
    acceptedFiles: "image/*",
    autoProcessQueue: false,
    init: function(){
      this.on("success", function (file) {
        location.reload();
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
	$("#userimages").click(function(){
		$(".shared-files").hide();
		$(".user-files").show();
	});
	
	$("#sharedimages").click(function(){
		$(".user-files").hide();
		$(".shared-files").show();
	});

    var dropzone = Dropzone.forElement("#imageupload");
    $(".upload-btn").click(function(){
      var name = escapeHTML($('#uploadname').val());
      var note = escapeHTML($('#uploadnote').val());
      $('#imageupload').append('<input type="hidden" name="name" value="' + name + '">');
      $('#imageupload').append('<input type="hidden" name="note" value="' + note + '">');
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
		var index = $(this).attr("index");
		$(".share-popup[index='" + index + "']").show();
	});
	
  $(".edit-btn").click(function(){
		var index = $(this).attr("index");
		$(".edit-popup[index='" + index + "']").show();
  });

    // When the close button is clicked, hide the popup
    $(".close-popup").click(function(){
       $(this).closest(".edit-popup").hide();
       $(this).closest(".media-popup").hide();
	   $(this).closest(".share-popup").hide();
    });

    document.addEventListener('click', function(event) {
      var target = event.target;
      if(target.classList.contains('show-media')) {
          var index = target.getAttribute('id');
          $('.media-popup').eq(index).show();
      }
    });
 });