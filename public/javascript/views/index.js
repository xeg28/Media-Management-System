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

function loadImage(container) {
    let picture = $(container).find(':last-child');
    let image = $(picture).find('img');
    if(image[0] && image[0].complete){
        $(container).addClass('loaded');
    }
    $(image).on('load', function() {
        $(container).addClass('loaded');
    });
}

function setTheme(val, toggle) {
    if(val % 3 == 0) {
        $(document.documentElement).removeAttr('theme');
        $(toggle).attr('val', '0');
        $(toggle).html("Theme: System 💻");
    }
    else if (val % 3 == 1) {
        $(document.documentElement).attr('theme', 'dark');
        $(toggle).attr('val', '1');
        $(toggle).html("Theme: Dark 🌙");
    }
    else {
        $(document.documentElement).attr('theme', 'light');
        $(toggle).attr('val', '2');
        $(toggle).html("Theme: Light ☀️");
    }
}
setTheme(localStorage.getItem('theme'), $('#theme'));
$(document).ready(function() {
    checkForPopups();
    setTheme(localStorage.getItem('theme'), $('#theme'));
    $('[data-toggle="tooltip"]').tooltip();

    // toggles theme
    $('#theme').on({
        click: function(event) {
            event.stopPropagation();
            let val = Number($(this).attr('val')) + 1;
            localStorage.setItem('theme', val);
            setTheme(val, this);
        },
        keypress: function(event) {
            if(event.which == 13) {
                event.stopPropagation();
                let val = Number($(this).attr('val')) + 1;
                localStorage.setItem('theme', val);
                setTheme(val, this);
            }
        }
    });

    function updatePreviews() {
        $(".preview-container").each(function (){
            var itemCount = $(this).children().length;
            if(itemCount < 3) {
                $(this).addClass('small-preview');
            }
            if(itemCount == 0) {
                this.closest('.wrapper').remove();
            }
            if($('.wrapper').length == 0) {
                let searchParam = new URLSearchParams(window.location.search);
                let filetype = (['Image', 'Audio', 'Video'].includes(document.title)) ? document.title.toLowerCase() + 's' : 'files';
                filetype = (searchParam.has('query')) ? 'results for ' + "'" + searchParam.get('query')+ "'" : filetype;
                $('.container-fluid').prepend('<div class="wrapper">'+
                  '<div class="no-files-wrapper">'+
                    '<h3 class="mb-4" >No '+filetype+' found</h3>'+
                    '<img src="'+window.location.origin+'/public/icons/no-content.png" alt="no content" class="mb-4 no-content-icon"/>'+
                    '<a class="btn-theme" href="http://localhost/upload" style=\'text-decoration: none;\'>Upload</a>'+
                    '</div>'+
                '</div>')
            }
        });
    }

    function deleteFile(delbtn) {
        var id = $(delbtn).attr("rowId");
        var preview = $(delbtn).closest(".file-preview");
        var index = $('.file-preview').index(preview);
        let sharePopup = $('.share-popup').eq(index);
        let container = $(delbtn).closest(".dynamic-container");
        var type = preview.attr("filetype");
        $.ajax({
            url: type+"/delete",
            type: "POST",
            data: {id: id},
            dataType: "json",
            success: function(response) { 
                preview.remove();
                if(response.filePreview) {
                    container.append(response.filePreview);
                    let newPopup = $(response.sharePopup);
                    newPopup.attr('last'+type.toLowerCase(), 'true');
                    let lastPopup = $('.share-popup[last'+type.toLowerCase()+'="true"]');
                    $(newPopup).insertAfter(lastPopup);
                    lastPopup.attr('last'+type.toLowerCase(),'false');
                    $newPreview = container.find(':last-child');
                    loadImage($newPreview.find('.blur-load'));
                }
                sharePopup.remove();

                updatePreviews();
            }
        });
    }


    $('body').on({
            click: function() {
                $(this).closest(".auth-alert").hide(); 
            }
    }, '.close-btn');

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
        loadImage(container);
    });

    $('.dynamic-container').on({
        click: function() {
            var index = $('.share-btn').index(this);
            $(".share-popup:eq(" + index + ")").show();
            console.log("showing " + index);
            if(scrollbarVisible(document.querySelector('html'))) {
                console.log(scrollbarVisible(document.querySelector('html')));
                $('html').addClass("no-scroll");
            }
        },
        keypress: function(e) {
            if(e.which == 13) {
                var index = $('.share-btn').index(this);
                var sharePopup = $(".share-popup:eq(" + index + ")");
                sharePopup.show();
                sharePopup.focus();
                if(scrollbarVisible(document.querySelector('html'))) {
                    $('html').addClass("no-scroll");
                }
            }
        }
    }, ".share-btn");

        // When the button is clicked, show the popup	
	$("body").on({
        click: function() {
            var index = $(".add-share-input").index(this);
            var form = $('.share-emails').eq(index);
            if(form.children().length < 10) 
                form.append('<input type="text" class="form-control mb-2 field" name="email[]" placeholder="Enter an email">');
        }       
	}, '.add-share-input');

    $("body").on( {
        click: function() {
		    var index = $(".remove-share-input").index(this);
		    var emails = $('.share-emails').eq(index);
            if(emails.children().length === 1) return; 
            var lastEmail = emails.find(':last-child');
            lastEmail.remove(); 
        }
	}, '.remove-share-input');
	
	$("body").on({
        click: function() {
            var index = $(".share-submit-btn").index($(this));
            $('.share-form').eq(index).submit();
        }
	}, '.share-submit-btn');  


    // When the close button is clicked, hide the popup
    $("body").on({
        click: function(){
            $(this).closest(".share-popup").hide();
            $('html').removeClass('no-scroll');
        }
    }, '.close-popup');
   
    // Deleting using ajax
    $(".dynamic-container").on({
        click: function() {
            deleteFile(this);
        },
        keypress: function(e) {
        if(e.which == 13) {
            deleteFile(this);
        }
    }
       
    }, '.del-btn');

    $('.dynamic-container').on({
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

    $('.dynamic-container').on({
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
    
    $('.dynamic-container').on({
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
        },
        keypress: function(e) {
            if(e.which == 13) {
                let dropdown = $(this).closest('.dropdown');
                if (dropdown.find('.dropdown-menu').hasClass("show")){
                    dropdown.find('.dropdown-menu').removeClass("show");
                    let optionsBtn = dropdown.find('.options');
                    dropdown.removeClass('show');
                    optionsBtn.attr('aria-expanded', 'false');
                    dropdown.find('dropdown-menu').removeClass('show');
                }
            }
        } 
    }, '.dropdown-item');

});