

function scrollbarVisible(element) {
    return element.scrollHeight > element.clientHeight;
  }

$(document).ready(function(){

    let fileContainer = document.getElementById('fileContainer');
    let image = (fileContainer) ? fileContainer.querySelector('img') : null;
   
    if(image) {
        function loadImage() {
            $('.loader').remove();
            $('.loader-wrapper').remove();
            $('.img-placeholder').remove();
            fileContainer.classList.add("loaded");
        }
        if(image.complete) {
            loadImage();
        }
        else {
            image.addEventListener('load', loadImage);
        }
    }

    $('#audioFile').click(function() {
        let file = document.getElementById("audioFile");
        if(file.paused) {
            file.play();
        }
        else {
            file.pause();
        }
    });

    $('.share-btn').click(function() {
        var index = $('.share-btn').index(this);
        // $(".share-popup[index='" + index + "']").show();
        $(".share-popup:eq(" + index + ")").show();
        if(scrollbarVisible(document.querySelector('html')))
            $('html').addClass("no-scroll");
    })

    $('.related-files').on({
        mousedown: function () {
            $(this).addClass("click-focus");
        }, 
        click: function () {
            let url = $(this).attr('url');
            window.location.href = url;
        }
    }, '.file-preview');

    $(".related-files").on({
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

    $('.edit-btn').click(function() {
        $(this).hide();
        $(this).siblings(".check-btn").show();
        $('#inputName').show();
        $('.file-name').hide();
        $('.note').hide();
        $('#inputNote').show();
    }); 
    $('.check-btn').click(function() {
        $('#editForm').submit()
    }); 

    $('.related-files').on({
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


    $('.related-files').on({
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
    }, '.dropdown-item');
});