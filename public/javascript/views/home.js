// Javascript for popups
$(document).ready(function(){
    $(".user-files-btn").click(function(){
        var index = $(".user-files-btn").index($(this));
		$(".shared-files").eq(index).hide();
		$(".user-files").eq(index).show();
	});
	
	$(".shared-files-btn").click(function(){
        var index = $(".shared-files-btn").index($(this));
		$(".user-files").eq(index).hide();
		$(".shared-files").eq(index).show();
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

      if(target.classList.contains("close-popup")) {
          var index = target.getAttribute("index");
          var element = document.getElementById("media"+index);
          if(element instanceof HTMLVideoElement || element instanceof HTMLAudioElement) {
              element.pause();
          }
      }
      if(target.classList.contains('show-media')) {
          var index = target.getAttribute('id');
          $('.media-popup').eq(index).show();
          var element = document.getElementById("media"+index);
          if(element instanceof HTMLVideoElement || element instanceof HTMLAudioElement) {
              element.play();
          }
      }
    });
 });