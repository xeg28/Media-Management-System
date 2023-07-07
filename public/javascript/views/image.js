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
  // Deleting using ajax
  $(".del-btn").click(function() {
    var id = $(this).attr("rowId");
    var row = $(this).closest(".card-data");
    var index = row.attr("index");
    var dataContainer = $(this).closest(".card-data-container");
    $.ajax({
      url: "/Image/delete",
      type: "POST",
      data: { id: id}, 
      dataType: 'json',
      success: function(response) { 
        row.remove(); 
        $(".share-popup[index='" + index + "']").remove();
        $(".edit-popup[index='" + index + "']").remove();
        $('.media-popup[index="'+index+'"]').remove();
        if (dataContainer.find(".card-data").length === 0) { 
            dataContainer.append("<p>No image(s) found...</p>");
        }
      }
    });
  });

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
          var index = target.getAttribute('index');
          $('.media-popup[index="'+index+'"]').show();
      }
    });
 });