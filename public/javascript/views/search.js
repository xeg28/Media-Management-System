
// Javascript for popups
$(document).ready(function(){
    // Deleting using ajax
    $('.del-btn').click(function() {
        var id  = $(this).attr("rowId");
        var row = $(this).closest(".card-data");
        var index = row.attr("index");
        var dataContainer = $(this).closest(".card-data-container");
        var type = row.attr("filetype");

        $.ajax({
            url: type+"/delete",
            type: "POST", 
            data: {id: id}, 
            dataType: 'json',
            success: function(response) {
                row.remove();
                $(".share-popup[index='" + index + "']").remove();
                $(".edit-popup[index='" + index + "']").remove();
                $('.media-popup[index="'+index+'"]').remove();
                if (dataContainer.find(".card-data").length === 0) { 
                    dataContainer.append("<p>No files(s) found...</p>");
                }
            }
        });
    });

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

      if(target.classList.contains("close-popup")) {
          var index = target.getAttribute("index");
          var element = document.getElementById("media"+index);
          if(element instanceof HTMLVideoElement || element instanceof HTMLAudioElement) {
              element.pause();
          }
      }
      if(target.classList.contains('show-media')) {
          var index = target.getAttribute('index');
          $('.media-popup[index="'+index+'"]').show();
          var element = document.getElementById("media"+index);
          if(element instanceof HTMLVideoElement || element instanceof HTMLAudioElement) {
              element.play();
          }
      }
    });
 });