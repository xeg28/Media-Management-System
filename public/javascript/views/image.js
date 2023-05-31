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

// Javascript for popups
$(document).ready(function(){
    // When the button is clicked, show the popup
    var dropzone = Dropzone.forElement("#imageupload");
    $(".upload-btn").click(function(){
      var name = escapeHTML($('#uploadname').val());
      var note = escapeHTML($('#uploadnote').val());
      $('#imageupload').append('<input type="hidden" name="name" value="' + name + '">');
      $('#imageupload').append('<input type="hidden" name="note" value="' + note + '">');
      dropzone.processQueue();
    });

    $(".edit-btn").click(function(){
      var index = $(".edit-btn").index($(this));
      console.log(index);
       $(".edit-popup").eq(index).show();
    });

    // When the close button is clicked, hide the popup
    $(".close-popup").click(function(){
       $(this).closest(".edit-popup").hide();
       $(this).closest(".media-popup").hide();
    });

    document.addEventListener('click', function(event) {
      var target = event.target;
      if(target.classList.contains('show-media')) {
          var index = target.getAttribute('id');
          $('.media-popup').eq(index).show();
      }
    });
 });