<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <script src="public/javascript/dropzone.min.js"></script>
    <script src="public/javascript/jquery-3.5.1.min.js"></script>
    <script src="public/javascript/bootstrap.bundle.min.js"></script>
    <script src="public/javascript/views/openfile.js"></script>


    <link rel="stylesheet" type="text/css" href="public/css/dropzone.min.css" /> 
    <?php if(in_array($title, array('Login', 'Registration'), true)): ?>
        <link rel="stylesheet" type="text/css" href="public/css/authentication.css?version=<%= Common.GetVersion%" /> 
    <?php endif?>
    <?php if(isset($file)): ?>
        <link rel="stylesheet" type="text/css" href="public/css/openfile.css?version=<%= Common.GetVersion%"/>
    <?php endif; ?>
    <?php if(isset($showNavbar) && $showNavbar): ?>
            <link rel="stylesheet" type="text/css" href="public/css/media-pages.css?version=<%= Common.GetVersion%"/>
            <link rel="stylesheet" type="text/css" href="public/css/navbar.css?version=<%= Common.GetVersion%"/>
            <link rel="stylesheet" type="text/css" href="public/css/share-popup.css?version=<%= Common.GetVersion%"/>
    <?php endif?>
    <link rel="stylesheet" type="text/css" href="public/css/style.css?version=<%= Common.GetVersion%" /> 
    <link rel="shortcut icon" href="public/icon.png" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <title><?php echo $title; ?></title>
</head>
<body>

<!-- This script prevents a user from submiting an empty form  -->
<script>
    document.addEventListener('submit', function(event) {
        var target = event.target;
        var elements = target.elements;
        for(let i = 0; i < elements.length; i++) {
            var element = elements[i];
            var value = element.value.trim();
            if(value === '' && element.classList.contains('field')) {
                event.preventDefault();
            }
        }     
        return;
    });
    $(document).ready(function() {
        $(".error-btn").click(function() {
            $(this).closest(".error-msg").hide();
        });

        $(".success-btn").click(function() {
            $(this).closest(".success-msg").hide();
        })
    });

</script>
<?php if(in_array($title, array('Login', 'Registration'))): ?>
    <script src="public/javascript/views/auth.js"></script>
<?php endif;?>
<?php if(isset($showNavbar) && $showNavbar): ?>
    <script src='public/javascript/views/index.js'></script>
<?php endif;?>
<?php if($title == 'Home') {
    ?> <script src='public/javascript/views/home.js'></script>
<?php } if($title == 'Image') { 
    ?> <script src='public/javascript/views/image.js'></script>
<?php } if($title == 'Audio') { 
    ?> <script src='public/javascript/views/audio.js'></script>
<?php } if($title == 'Video') { 
    ?> <script src='public/javascript/views/video.js'></script>
<?php } if($title == 'Search Results') { 
    ?> <script src='public/javascript/views/search.js'></script>
<?php } ?>
    

<?php if (isset($showNavbar) && $showNavbar): ?>
    <?php
$current_page = basename($_SERVER['REQUEST_URI'], ".php");
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a href="<?=base_url('home')?>"><img class="home-shortcut mx-3 navbar-brand logo" src="public/icon.png" alt="Media-icon" /></a>
    <button class="navbar-toggler mx-3" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse mx-3" id="navbarText">
        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll">
            <li class="nav-item <?php if($current_page == 'home') {echo 'active';} ?>">
                <a class="nav-link" href="<?=base_url('/home')?>">Home</a>
            </li>
            <li class="nav-item <?php if($current_page == 'image') {echo 'active';} ?>">
                <a class="nav-link" href="<?=base_url('image')?>">Image</a>
            </li>
            <li class="nav-item <?php if($current_page == 'audio') {echo 'active';} ?>">
                <a class="nav-link" href="<?=base_url('/audio')?>">Audio</a>
            </li>
            <li class="nav-item <?php if($current_page == 'video') {echo 'active';} ?>">
                <a class="nav-link" href="<?=base_url('/video')?>">Video</a>
            </li>
            
            <div class="d-flex mt-4 justify-content-between px-2 w-100">
                <form class="d-lg-none d-flex w-75" action="<?=base_url()?>/search" method="get">
                    <div class="input-group">
                        <input type="search" class="form-control field" name="query" placeholder="Search..." aria-label="Search">
                        <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
                <a class="nav-link d-lg-none" href="<?=base_url('/logout')?>">Logout</a>
            </div>
           
        </ul>
    </div>

    <form class="d-none d-lg-block" action="<?=base_url()?>/search" method="get">
        <div class="input-group">
            <input type="search" class="form-control field" name="query" placeholder="Search..." aria-label="Search">
            <div class="input-group-append">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
    
     <a class="nav-item nav-link px-5 d-none d-lg-block" href="<?=base_url('/logout')?>">Logout</a>


</nav>
<?php if (isset($errors)): ?>
  <div class="error-msg popup">
    <div class="popup-content">
      <div class="alert alert-danger" role="alert">
        <span class="close-btn error-btn">&times;</span>
        <div class="row pt-2 pb-2">
          <?php foreach ($errors as $error) {
            echo $error;
          } ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php endif; ?>

