<div class="container-fluid mt-3" id='main-container'>
	<?php
	$index = 0;
	?>
	<div class="wrapper">
		<h5>Search results for <strong>
				<?= $query ?>
			</strong></h5>

		<div class="preview-container <?= $preview === 'small' ? 'small-preview' : ''?>">
			<?php
			if (!empty($files)) {
				foreach ($files as $row) {
					// $upload_time = new DateTime($row->uploaded_at);
					// $date = new DateTime();
					// $difference = get_time_difference($date, $upload_time);
					// $filetype = explode('/', $row->type)[1];
					// $thumbnail = base_url('public/' . strtolower($row->filetype) . '/thumbnails/' . explode('.', $row->caption)[0] . '.png');
					// $file = ROOTPATH . 'public/' . strtolower($row->filetype) . '/thumbnails/' . explode('.', $row->caption)[0] . '.png';
					// $thumbnail = (file_exists($file)) ? $thumbnail : base_url('public/' . strtolower($row->filetype) . '/icon.png');
					// $thumbnails = [
					// 	'Image' => base_url('writable/uploads/images/' . $row->caption),
					// 	'Audio' => $thumbnail,
					// 	'Video' => $thumbnail
					// ];
					// $filepaths = [
					// 	'Image' => base_url('public/images/'),
					// 	'Audio' => base_url('public/audio/'),
					// 	'Video' => base_url('public/video/')
					// ];
					echo createFilePreview($row);
					?>
					

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