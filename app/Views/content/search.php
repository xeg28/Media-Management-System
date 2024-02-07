<div class="container-fluid mt-3" id='main-container'>
	<?php
	$index = 0;
	?>
	<div class="wrapper">
		<h5>Search results for <strong>
				<?= $query ?>
			</strong></h5>

		<div class="preview-container">
			<?php
			if (!empty($files)) {
				foreach ($files as $row) {
					$upload_time = new DateTime($row->uploaded_at);
					$date = new DateTime();
					$difference = get_time_difference($date, $upload_time);
					$filetype = explode('/', $row->type)[1];
					$thumbnail = base_url('public/' . strtolower($row->filetype) . '/thumbnails/' . explode('.', $row->caption)[0] . '.png');
					$file = ROOTPATH . 'public/' . strtolower($row->filetype) . '/thumbnails/' . explode('.', $row->caption)[0] . '.png';
					$thumbnail = (file_exists($file)) ? $thumbnail : base_url('public/' . strtolower($row->filetype) . '/icon.png');
					$thumbnails = [
						'Image' => base_url('public/images/' . $row->caption),
						'Audio' => $thumbnail,
						'Video' => $thumbnail
					];
					$filepaths = [
						'Image' => base_url('public/images/'),
						'Audio' => base_url('public/audio/'),
						'Video' => base_url('public/video/')
					];
					?>
					<div class="file-preview" filetype="<?= $row->filetype ?>"
						url="<?= base_url('/Open' . $row->filetype . '?id=' . $row->id) ?>">
						<div class="img-wrapper">
							<?php if ($row->is_shared == 1): ?>
								<svg width="20px" height="20px" viewBox="0 0 16 16" class="shared-icon" fill="">
									<path d="M5,7 C6.11,7 7,6.1 7,5 C7,3.9 6.11,3 5,3 C3.9,3 3,3.9 3,5 C3,6.1 3.9,7 5,7
										 L5,7 Z M11,7 C12.11,7 13,6.1 13,5 C13,3.9 12.11,3 11,3 C9.89,3 9,3.9 9,5 C9,6.1 9.9,7
											11,7 L11,7 Z M5,8.2 C3.33,8.2 0,9.03 0,10.7 L0,12 L10,12 L10,10.7 C10,9.03 6.67,8.2 5,8.2
											 L5,8.2 Z M11,8.2 C10.75,8.2 10.46,8.22 10.16,8.26 C10.95,8.86 11.5,9.66 11.5,10.7
												L11.5,12 L16,12 L16,10.7 C16,9.03 12.67,8.2 11,8.2 L11,8.2 Z"></path>
								</svg>
								<span class="shared-hover">Shared by
									<?= $row->sender_email ?>
								</span>
							<?php endif; ?>
							<img class="<?= strtolower($row->filetype) ?>-icon" src="<?= $filepaths[$row->filetype] . '/icon.svg'; ?>"
								draggable="false">
							<?php if(in_array($row->filetype, array("Audio", "Video"), true)):?>
								<span class="duration-text"><?=trimDurationText($row->duration)?></span>
							<?php endif ?>
							<img src="<?= $thumbnails[$row->filetype] ?>" type="image/png" draggable="false" style="object-fit: contain;" />
						</div>

						<div class="d-flex flex-row justify-content-between w-100">
							<div class="d-flex flex-column m-2">
								<span class="preview-title">
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
								<div class="d-flex flex-column justify-content-end">
									<ul class="dropdown-menu">
										<?php if ($row->is_shared == 0): ?>
											<li><a class="dropdown-item share-btn" index="<?= $index ?>">Share</a></li>
											<li><a class="dropdown-item del-btn" rowId="<?= $row->id ?>">Delete</a></li>
										<?php endif; ?>
										<li><a class="dropdown-item"
												href="<?= base_url('/Download' . $row->filetype . '?id=' . $row->id) ?>">Download</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>

					<?php
					$index++;
				} ?>
				<?php
			} else {
				?>
				<p>No files(s) found...
				</p>
			<?php } ?>
		</div>
	</div>
</div>