<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="color-scheme" content="light dark">
	<link rel="stylesheet" href="public/css/bootstrap.min.css">
	<script src="public/javascript/dropzone.min.js"></script>
	<script src="public/javascript/jquery-3.5.1.min.js"></script>
	<script src="public/javascript/bootstrap.bundle.min.js"></script>
	<script src="public/javascript/views/openfile.js"></script>


	<link rel="stylesheet" type="text/css" href="public/css/dropzone.min.css" />
	<?php if (in_array($title, array('Login', 'Registration'), true)): ?>
		<link rel="stylesheet" type="text/css" href="public/css/authentication.css?version=<%= Common.GetVersion%" />
	<?php endif ?>
	<?php if (isset($file)): ?>
		<link rel="stylesheet" type="text/css" href="public/css/openfile.css?version=<%= Common.GetVersion%" />
	<?php endif; ?>
	<?php if (isset($showNavbar) && $showNavbar): ?>
		<link rel="stylesheet" type="text/css" href="public/css/media-pages.css?version=<%= Common.GetVersion%" />
		<link rel="stylesheet" type="text/css" href="public/css/navbar.css?version=<%= Common.GetVersion%" />
		<link rel="stylesheet" type="text/css" href="public/css/share-popup.css?version=<%= Common.GetVersion%" />
	<?php endif ?>
	<link rel="stylesheet" type="text/css" href="public/css/style.css?version=<%= Common.GetVersion%" />
	<link rel="shortcut icon" href="public/icon.png" type="image/x-icon">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
	<title>
		<?php echo $title; ?>
	</title>
</head>

<body>

	<!-- This script prevents a user from submiting an empty form  -->
	<script>
		document.addEventListener('submit', function (event) {
			var target = event.target;
			var elements = target.elements;
			for (let i = 0; i < elements.length; i++) {
				var element = elements[i];
				var value = element.value.trim();
				if (value === '' && element.classList.contains('field')) {
					event.preventDefault();
				}
			}
			return;
		});
	</script>
<script src='public/javascript/views/index.js'></script>		
	<?php if($title == 'Upload') { ?>
		<script src='public/javascript/views/upload.js'></script>
	<?php }
	if ($title == 'Home') {
		?>
		<script src='public/javascript/views/home.js'></script>
	<?php }
	if ($title == 'Image') {
		?>
		<script src='public/javascript/views/image.js'></script>
	<?php }
	if ($title == 'Audio') {
		?>
		<script src='public/javascript/views/audio.js'></script>
	<?php }
	if ($title == 'Video') {
		?>
		<script src='public/javascript/views/video.js'></script>
	<?php }
	if ($title == 'Search Results') {
		?>
		<script src='public/javascript/views/search.js'></script>
	<?php } ?>


	<?php if (isset($showNavbar) && $showNavbar): ?>
		<?php
		$current_page = basename($_SERVER['REQUEST_URI'], ".php");
		?>

		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<a class="d-lg-none" href="<?= base_url('home') ?>"><img class="home-shortcut mx-2 navbar-brand logo"
					src="public/icon.png" alt="Media-icon" /></a>

			<div class="collapse navbar-collapse" id="navbarText">
				<a class="d-none d-lg-block" href="<?= base_url('home') ?>"><img class="home-shortcut mx-3 navbar-brand logo"
						src="public/icon.png" alt="Media-icon" /></a>
				<ul class="navbar-nav me-auto mx-2 my-2 my-lg-0 navbar-nav-scroll">
					<li class="nav-item <?php if ($current_page == 'home'):
						echo 'active';
					endif; ?>">
						<a class="nav-link" href="<?= base_url('/home') ?>">Home</a>
					</li>
					<li class="nav-item <?php if ($current_page == 'image'):
						echo 'active';
					endif; ?>">
						<a class="nav-link" href="<?= base_url('image') ?>">Image</a>
					</li>
					<li class="nav-item <?php if ($current_page == 'audio'):
						echo 'active';
					endif;?>">
						<a class="nav-link" href="<?= base_url('/audio') ?>">Audio</a>
					</li>
					<li class="nav-item <?php if ($current_page == 'video'):
						echo 'active';
					endif;?>">
						<a class="nav-link" href="<?= base_url('/video') ?>">Video</a>
					</li>
					<li class="nav-item <?php if($current_page == 'upload'): 
					 echo 'active';
					endif; ?>">
						<a href="<?=base_url('/upload')?>" class="nav-link">Upload</a>
					</li>
				</ul>
			</div>

			<div class="d-none d-md-block ml-lg-2 search-container">
				<form class="d-flex" action="<?= base_url() ?>/search" method="get">
					<div class="input-group">
						<input type="search" class="form-control field" name="query" placeholder="Search..." aria-label="Search">
						<div class="input-group-append">
							<button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
						</div>
					</div>
				</form>
			</div>

			<div class="nav-btns">
				<img class="search-btn search-icon d-md-none" src="<?= base_url('public/icons/search.svg') ?>"
					draggable="false"></img>
				<button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarText"
					aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="dropdown collapsed">
					<div class="nav-options" data-toggle="dropdown" aria-expanded="false">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M12 16.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5
									1.5-1.5zM10.5 12c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5-.67-1.5-1.5-1.5-1.5.67-1.5
									1.5zm0-6c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5-.67-1.5-1.5-1.5-1.5.67-1.5 1.5z"></path>
						</svg>
					</div>
					<div class="d-flex flex-column justify-content-end">
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" id='theme' val="0">Theme: System 💻</a></li>
							<li><a class="dropdown-item" href="<?= base_url('/logout') ?>">Logout</a></li>
						</ul>
					</div>
				</div>
			</div>
		</nav>

		<div class="nav-search">
			<img class="back-btn back-icon" src="<?= base_url('public/icons/back.svg') ?>" draggable="false"></img>
			<form class="d-flex" action="<?= base_url() ?>/search" method="get">
				<div class="input-group">
					<input type="search" id="searchField" class="form-control field" name="query" placeholder="Search..."
						aria-label="Search">
					<div class="input-group-append">
						<button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
					</div>
				</div>
			</form>
		</div>


		<?php if (isset($errors)): ?>
			<div class="popup">
				<div class="alert-box">
					<img src="<?=base_url('public/icons/alert.png')?>"/>
					<span class="alert-title">ops!</span>
						<?php foreach ($errors as $error) {
							echo '<p>'.$error.'</p>';
						} ?>
						<button class="alert-btn error-btn" >OK</button>
				</div>
			</div>
		<?php endif; ?>

	<?php endif; ?>