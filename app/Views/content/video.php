<div class="container-fluid mt-3">
	
	<?php if(sizeof($files) == 0):?>
		<div class="wrapper">
			<div class="no-files-wrapper">
				<h3 class="mb-4" >No videos found</h3>
				<img src="<?=base_url('public/icons/no-content.png')?>" alt="no content" class="mb-4 no-content-icon">
				<a class="btn-theme" href="<?=base_url('/upload')?>" style='text-decoration: none;'>Upload</a>
			</div>
		</div>
	<?php endif; ?>

	<?php if (!empty($files)) { ?>
	<div class="wrapper">
		<div class="d-flex align-items-center mb-2">
			<h5>Video Files</h5>
		</div>
		<div class="preview-container dynamic-container <?=$vidPreview === "small" ? 'small-preview' : ''?>">
			<?php
				$index = 0;
				foreach ($files as $row) {
					echo createFilePreview($row);
				} ?>
			</div>
		</div>
	<?php } ?>
</div>