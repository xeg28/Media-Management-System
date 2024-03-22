

function scrollbarVisible(element) {
    return element.scrollHeight > element.clientHeight;
  }

$(document).ready(function(){

    $('html').on({
        keydown: function(e) {
            let file = document.querySelector(".playable");
            
            if(!file || document.activeElement == file || ![37,39,32].includes(e.which)) return;
            
            if(!['input', 'textarea'].includes(document.activeElement.tagName.toLowerCase())) e.preventDefault();
            else return;

            if(e.which == 37) {
                console.log(file);
                file.currentTime -= 5;
            }
            if(e.which == 39) {
                file.currentTime += 5;
            }
            
            if(e.which == 32) {
                if(file.paused) file.play();
                else file.pause();
            }
        }
    });
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

    $('.share-btn').on({
        click: function(){
                var index = $('.share-btn').index(this);
                $(".share-popup:eq(" + index + ")").show();
                if(scrollbarVisible(document.querySelector('html')))
                    $('html').addClass("no-scroll");
            }, 
            keypress: function(e) {
                if(e.which == 13) {
                    var index = $('.share-btn').index(this);
                $(".share-popup:eq(" + index + ")").show();
                if(scrollbarVisible(document.querySelector('html')))
                    $('html').addClass("no-scroll");
                }
            }
    });

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

});