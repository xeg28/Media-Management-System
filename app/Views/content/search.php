<div class="container-fluid mt-3" id='main-container'>
	<?php if(sizeof($files) == 0):?>
		<div class="wrapper">
			<div class="no-files-wrapper">
				<h3 class="mb-4" >No results for '<?= $query ?>' found</h3>
				<img src="<?=base_url('public/icons/no-content.png')?>" alt="no content" class="mb-4 no-content-icon">
				<a class="btn-theme" href="<?=base_url('/upload')?>" style='text-decoration: none;'>Upload</a>
			</div>
		</div>
	<?php endif; ?>
	
	<?php
	$index = 0;
	
	if (!empty($files)) { ?>
	
	<div class="wrapper">
		<h5>Search results for <strong>
				<?= $query ?>
			</strong></h5>

		<div class="preview-container dynamic-container <?= $preview === 'small' ? 'small-preview' : ''?>">
			<?php
				foreach ($files as $row) {
					echo createFilePreview($row);
					?>
					<?php
					$index++;
				} ?>
			</div>
		</div>
	<?php }?>
</div>