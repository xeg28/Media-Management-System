<div class="container-fluid mt-3">

	<?php if(sizeof($files) == 0):?>
		<div class="wrapper">
			<div class="no-files-wrapper">
				<h3 class="mb-4" >No audios found</h3>
				<img src="<?=base_url('public/icons/no-content.png')?>" alt="no content" class="mb-4 no-content-icon">
				<a class="btn-theme" href="<?=base_url('/upload')?>" style='text-decoration: none;'>Upload</a>
			</div>
		</div>
  <?php endif; ?>

	<?php if (!empty($files)) {?>
	<div class="wrapper">
		<div class="d-flex align-items-center mb-2">
				<h5>Audio Files</h5>
		</div>
		<div class="preview-container <?=$audPreview === "small" ? 'small-preview' : ''?>">
			<?php
				$index = 0;
				foreach ($files as $row) {
					$upload_time = new DateTime($row->uploaded_at);
					$date = new DateTime();
					$difference = get_time_difference($date, $upload_time);
					$filetype = explode('/', $row->type)[1];
					?>
					<div class="file-preview" filetype="<?= $row->filetype ?>"
						url="<?= base_url('/Open' . $row->filetype . '?id=' . $row->id) ?>">
						<div class="img-wrapper blur-load" <?=($row->sender_email != '0') ?'title="Shared by '.$row->sender_email.'"': ''?>>
							<?php if ($row->is_shared == 1): ?>
								<svg width="20px" height="20px" viewBox="0 0 16 16" class="shared-icon" fill="">
									<path d="M5,7 C6.11,7 7,6.1 7,5 C7,3.9 6.11,3 5,3 C3.9,3 3,3.9 3,5 C3,6.1 3.9,7 5,7
									 L5,7 Z M11,7 C12.11,7 13,6.1 13,5 C13,3.9 12.11,3 11,3 C9.89,3 9,3.9 9,5 C9,6.1 9.9,7
										11,7 L11,7 Z M5,8.2 C3.33,8.2 0,9.03 0,10.7 L0,12 L10,12 L10,10.7 C10,9.03 6.67,8.2 5,8.2
										 L5,8.2 Z M11,8.2 C10.75,8.2 10.46,8.22 10.16,8.26 C10.95,8.86 11.5,9.66 11.5,10.7
											L11.5,12 L16,12 L16,10.7 C16,9.03 12.67,8.2 11,8.2 L11,8.2 Z"></path>
								</svg>
							<?php endif; ?>
							<span class="duration-text"><?=trimDurationText($row->duration)?></span>
							<img class="image-icon" src="<?= base_url('public/audio/icon.svg'); ?>" draggable="false">
							<picture>
								<img src="<?php echo base_url('public/audio/icon.png'); ?>" type="image/png" draggable="false"
									style="object-fit: contain;" />
							</picture>
						</div>

						<div class="d-flex flex-row justify-content-between w-100">
							<div class="d-flex flex-column m-2">
								<span class="preview-title" title="<?=$row->name?>">
									<?= htmlspecialchars($row->name) ?>
								</span>
								<span>Format:
									<?= $filetype ?> &#8226;
									<?php echo $difference; ?>
								</span>
							</div>
							<div class="dropdown">
								<div class="options" index="<?= $index ?>" data-toggle="dropdown" aria-expanded="false">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
										<path d="M12 16.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5
										1.5-1.5zM10.5 12c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5-.67-1.5-1.5-1.5-1.5.67-1.5
											1.5zm0-6c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5-.67-1.5-1.5-1.5-1.5.67-1.5 1.5z"></path>
									</svg>
								</div>
								<div class="d-flex flex-column justifty-content-end">
									<ul class="dropdown-menu">
										<?php if ($row->is_shared == 0): ?>
											<li><a class="dropdown-item share-btn" index="<?= $index ?>">Share</a></li>
											<li><a class="dropdown-item del-btn" rowId="<?= $row->id ?>">Delete</a></li>
										<?php endif; ?>
										<li><a class="dropdown-item" href="<?= base_url('/DownloadAudio?id=' . $row->id) ?>">Download</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<?php
					$index++;
				}
				?>
			</div>
		</div>
			<?php 
				} 
			?>
</div>