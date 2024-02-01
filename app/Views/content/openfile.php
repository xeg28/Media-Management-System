<div class="container-fluid">
	<div class="file-container">
		<?php if (explode('/', $file->filetype)[0] == 'image'): ?>
			<img src="<?= base_url('public/images/' . $file->caption) ?>" alt="Alternative Text for Image">
		<?php endif; ?>
		<?php if (explode('/', $file->filetype)[0] == 'audio'): ?>
			<audio id="audioFile" controls autoplay="autoplay"><source src="public/audio/<?=$file->caption?>" type="<?=$file->type?>"></audio>
		<?php endif; ?>
		<?php if (explode('/', $file->filetype)[0] == 'video'): ?>
			<video id="" controls autoplay="autoplay"><source src="public/video/<?=$file->caption?>" type="<?=$file->type?>"></video>
		<?php endif; ?>
	</div>

	<div class="d-flex flex-column flex-sm-row w-100">
		<div class="file-info">
			<h4 class="file-name"><?=$file->name?></h4>
			<div class="description">
				<p><strong>Format: <?=explode('/', $file->type)[1]?> &#8226; <?=dateTimeToDate($file->uploaded_at)?></strong></p>
				<p><?=$file->note?></p>
			</div>
		</div>
		<div class="related-files">
			<h4>Related Files</h4>
		</div>
	</div>
	
</div>