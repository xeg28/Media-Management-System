function checkForPopups() {
    if($('.popup').length > 0) {
        $('html').addClass("no-scroll");
    }
}

$(document).ready(function() {
    checkForPopups();

    function updatePreviews() {
        $(".preview-container").each(function (){
            var itemCount = $(this).children().length;
            if(itemCount < 3) {
                $(this).addClass('small-preview');
            }
        });
    }

    $('.close-btn').click(function() {
        $(this).closest(".auth-alert").hide();
    })
   $('.alert-btn').click(function() {
        $('.popup').hide();
        $('html').removeClass("no-scroll");
   });

    $('.search-btn').click(function() {
        $('.navbar').hide();
        $('.nav-search').addClass('shown');
        $('#searchField').focus();
    });

    $('.back-btn').click(function() {
        $('.navbar').show();
        $('.nav-search').removeClass('shown');
    });

    $(".share-btn").click(function() {
        var index = $(this).attr("index");
        $(".share-popup[index='" + index + "']").show();
        console.log("showing " + index);
        $('html').addClass("no-scroll");
    });

        // When the button is clicked, show the popup	
	$(".add-share-input").click(function() {
		var index = $(".add-share-input").index($(this));
		var form = $('.share-emails').eq(index);
		form.append('<input type="text" class="form-control mb-2 field" name="email[]" placeholder="Enter an email">');
	});

    $(".remove-share-input").click(function() {
		var index = $(".remove-share-input").index($(this));
		var emails = $('.share-emails').eq(index);
        if(emails.children().length === 1) return; 
        var lastEmail = emails.find(':last-child');
        lastEmail.remove();
	});
	
	$(".share-submit-btn").click(function() {
		var index = $(".share-submit-btn").index($(this));
		$('.share-form').eq(index).submit();
	});  

    $(".edit-btn").click(function(){
      var index = $(this).attr("index");
       $(".edit-popup[index='" + index + "']").show();
    });

    // When the close button is clicked, hide the popup
    $(".close-popup").click(function(){
       $(this).closest(".share-popup").hide();
       $('html').removeClass('no-scroll');
    });
   
    // Deleting using ajax
    $(".del-btn").click(function() {
        var id = $(this).attr("rowId");
        var preview = $(this).closest(".file-preview");
        let container = $(this).closest(".preview-container");
        var type = preview.attr("filetype");
        $.ajax({
            url: type+"/delete",
            type: "POST",
            data: {id: id},
            dataType: "json",
            success: function(response) { 
                preview.remove();
                // $(".edit-popup[index='" + index + "']").remove();
                if (container.find(".file-preview").length === 0) { 
                    container.append("<p>No "+type.toLowerCase()+"s(s) found...</p>");
                }
                updatePreviews();
             }
        });
    });

    $('.file-preview').on({
        mousedown: function () {
            $(this).addClass("click-focus");
        }, 
        click: function () {
            let url = $(this).attr('url');
            window.location.href = url;
        }
    });

    $('body').on({
        mouseup: function(event) {
            let focused = $('.click-focus');
            focused.removeClass("click-focus")
        }
    })

    $('.options').on({
        mousedown: function (event) {
            event.stopPropagation();
            $(this).addClass("click-focus");
        }, 
        mouseup: function (event) {
            event.stopPropagation();
        },
        click: function( event) {
            event.stopPropagation();
            $(this).removeClass("click-focus");
            let dropdown = $(this).closest('.dropdown');
            let index = $(this).attr('index');
            if (dropdown.find('.dropdown-menu').is(":hidden")){
                if(!dropdown.find('.dropdown-menu').attr('style'))
                    $(this).dropdown('toggle');
                if($(this).attr('aria-expanded') == 'false') {
                    dropdown.addClass('show');
                    $(this).attr('aria-expanded', 'true');
                    dropdown.find('dropdown-menu', 'show');
                }
              }
            }
    });
    
    $('.dropdown-item').on({
        mousedown: function(event) {
            event.stopPropagation();
        },
        click: function(event) {
            event.stopPropagation();
            let dropdown = $(this).closest('.dropdown');

            if (dropdown.find('.dropdown-menu').hasClass("show")){
                dropdown.find('.dropdown-menu').removeClass("show");
                let optionsBtn = dropdown.find('.options');
                console.log(optionsBtn);
                dropdown.removeClass('show');
                optionsBtn.attr('aria-expanded', 'false');
                dropdown.find('dropdown-menu').removeClass('show');
            }
        }
    });

});