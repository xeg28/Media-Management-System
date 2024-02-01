$(document).ready(function(){
    function play() {
        let file = document.getElementById("audioFile");
        if(file.paused) {
            file.play();
        }
        else {
            file.pause();
        }
        
    }

    // Trigger play when the document is fully loaded
    $(window).on("load", function() {
        $('#audioFile').click(play);
    });

});