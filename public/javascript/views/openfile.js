$(document).ready(function(){
    $('#audioFile').click(function() {
        let file = document.getElementById("audioFile");
        if(file.paused) {
            file.play();
        }
        else {
            file.pause();
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