function scrollbarVisible(element) {
    return element.scrollHeight > element.clientHeight;
  }

function checkForPopups() {
    if($('.popup').length <= 0) {
       return;
    }
    if(scrollbarVisible(document.querySelector('html')))
        $('html').addClass("no-scroll");
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

    $('.blur-load').each(function(index, container) {
        let picture = $(container).find(':last-child');
        let image = $(picture).find('img');
        if(image[0] && image[0].complete){
            $(container).addClass('loaded');
        }
        $(image).on('load', function() {
            $(container).addClass('loaded');
        });
    });

    $('.preview-container').on({
        click: function() {
            // var index = $(this).attr("index");
            var index = $('.share-btn').index(this);
            // $(".share-popup[index='" + index + "']").show();
            $(".share-popup:eq(" + index + ")").show();
            console.log("showing " + index);
            if(scrollbarVisible(document.querySelector('html'))) {
                console.log(scrollbarVisible(document.querySelector('html')));
                $('html').addClass("no-scroll");
            }
        }
    }, ".share-btn");

        // When the button is clicked, show the popup	
	$(".add-share-input").click(function() {
		var index = $(".add-share-input").index(this);
		var form = $('.share-emails').eq(index);
        if(form.children().length < 10) 
		    form.append('<input type="text" class="form-control mb-2 field" name="email[]" placeholder="Enter an email">');
	});

    $(".remove-share-input").click(function() {
		var index = $(".remove-share-input").index(this);
		var emails = $('.share-emails').eq(index);
        if(emails.children().length === 1) return; 
        var lastEmail = emails.find(':last-child');
        lastEmail.remove();
	});
	
	$(".share-submit-btn").click(function() {
		var index = $(".share-submit-btn").index($(this));
		$('.share-form').eq(index).submit();
	});  

    // $(".edit-btn").click(function(){
    //   var index = $(this).attr("index");
    //    $(".edit-popup[index='" + index + "']").show();
    // });

    // When the close button is clicked, hide the popup
    $(".close-popup").click(function(){
       $(this).closest(".share-popup").hide();
       $('html').removeClass('no-scroll');
    });
   
    // Deleting using ajax
    $(".preview-container").on({
        click: function() {
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
                    if (container.find(".file-preview").length === 0) { 
                        container.append("<p>No "+type.toLowerCase()+"s(s) found...</p>");
                    }
                    console.log(response.images);
                    if(response.filePreview) {
                        container.append(response.filePreview);
                        $('body').append(response.sharePopup);
                    }
    
                    updatePreviews();
                 }
            });
        }
       
    }, '.del-btn');

    $('.preview-container').on({
        mousedown: function () {
            $(this).addClass("click-focus");
        }, 
        click: function () {
            let url = $(this).attr('url');
            window.location.href = url;
        }
    }, '.file-preview');

    $('body').on({
        mouseup: function(event) {
            let focused = $('.click-focus');
            focused.removeClass("click-focus");
        }
    })

    $('.preview-container').on({
        mousedown: function (event) {
            event.stopPropagation();
            $(this).addClass("click-focus");
        }, 
        click: function( event) {
            event.stopPropagation();
            $(this).removeClass("click-focus");
            let dropdown = $(this).closest('.dropdown');
            let index = $('.options').index(this);
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
    }, '.options');
    
    $('.preview-container').on({
        mousedown: function(event) {
            event.stopPropagation();
        },
        click: function(event) {
            event.stopPropagation();
            let dropdown = $(this).closest('.dropdown');

            if (dropdown.find('.dropdown-menu').hasClass("show")){
                dropdown.find('.dropdown-menu').removeClass("show");
                let optionsBtn = dropdown.find('.options');
                dropdown.removeClass('show');
                optionsBtn.attr('aria-expanded', 'false');
                dropdown.find('dropdown-menu').removeClass('show');
            }
        }
    }, '.dropdown-item');

});