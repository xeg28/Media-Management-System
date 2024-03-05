<?php

function createImagePlaceholder($img) {
	$image = \Config\Services::image();
	$image->withFile($img);
	$width = $image->getWidth();
	$height = $image->getHeight();

	return '<svg class="img-placeholder" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$width.' '.$height.'">
					<rect x = "0" y = "0" width="'.$width.'" height="'.$height.'" fill="#2f2f2f" />
					<div class="loader-wrapper"><span class="loader"></span></div>
				</svg>';
}

function createSharePopup($file) {
	if(!$file) {
		return '';
	}

	return '<div class="share-popup">
						<div class="share-popup-content position-relative">
							<div class="card">
								<div class="card-header">
									<h5>Share '.$file->name.'</h5>'
							. '</div>'	
							.	'<span class="close-popup">&times;</span>'
							.	'<div class="card-body">'
							.		'<form action="'.base_url('Share'.$file->filetype).'" method="post" class="form share-form">
										<div>
											<div class="d-flex justify-content-between">
												<label>User\'s Email</label>
												<div>
													<span class="add-share-input share-input-btn">&plus;</span>
													<span class="remove-share-input share-input-btn">&minus;</span>		
												</div>
											</div>
											<input type="hidden" name="id" value="'.$file->id.'">
											<div class="share-emails">
												<input type="text" class="form-control mb-2 field" name="email[]" placeholder="Enter an email">
											</div>
										</div>
									</form>
									<div class="row d-flex justify-content-center">
										<button class="btn btn-theme mt-4 mx-auto share-submit-btn">Share</button>
									</div>'
							.	'</div'
						.'</div>'
					. '</div>'
					.'</div>';

}

function createFilePreview($file)
{
	if(!$file) {
		return '';
	}

	$thumbnail = '';
	if($file->filetype != 'Image') {
		$thumbnail = base_url('public/' . strtolower($file->filetype) . '/thumbnails/' . explode('.', $file->caption)[0] . '.png');
		$filePath = ROOTPATH . 'public/' . strtolower($file->filetype) . '/thumbnails/' . explode('.', $file->caption)[0] . '.png';
		$thumbnail = (file_exists($filePath)) ? $thumbnail : base_url('public/' . strtolower($file->filetype) . '/icon.png');
	}
	else {
		$thumbnail = getThumbnailURL($file->caption, 'images');
	}

	$iconpaths = [
		'Image' => base_url('public/images/'),
		'Audio' => base_url('public/audio/'),
		'Video' => base_url('public/video/')
	];

	$upload_time = new DateTime($file->uploaded_at);
	$date = new DateTime();
	$difference = get_time_difference($date, $upload_time);
	$filetype = explode('/', $file->type)[1];

	return '<div class="file-preview" filetype="'.$file->filetype.'"'
					.'url="'.base_url('/Open'.$file->filetype.'?id='.$file->id).'">'
						.'<div class="img-wrapper blur-load" '.(($file->is_shared == 1) ? 'title="Shared by' .$file->sender_email .'"': ''). '>'
						. (($file->is_shared == 1) ? 
						'<svg width="20px" height="20px" viewBox="0 0 16 16" class="shared-icon" fill="">
							<path d="M5,7 C6.11,7 7,6.1 7,5 C7,3.9 6.11,3 5,3 C3.9,3 3,3.9 3,5 C3,6.1 3.9,7 5,7
								L5,7 Z M11,7 C12.11,7 13,6.1 13,5 C13,3.9 12.11,3 11,3 C9.89,3 9,3.9 9,5 C9,6.1 9.9,7
									11,7 L11,7 Z M5,8.2 C3.33,8.2 0,9.03 0,10.7 L0,12 L10,12 L10,10.7 C10,9.03 6.67,8.2 5,8.2
									L5,8.2 Z M11,8.2 C10.75,8.2 10.46,8.22 10.16,8.26 C10.95,8.86 11.5,9.66 11.5,10.7
										L11.5,12 L16,12 L16,10.7 C16,9.03 12.67,8.2 11,8.2 L11,8.2 Z"></path>
						</svg>' : '')
						.'<img class="' . strtolower($file->filetype) .'-icon" src="'. $iconpaths[$file->filetype] .'/icon.svg"'.
							'draggable="false"/>' 
							.(($file->filetype != 'Image') ? 
							'<span class="duration-text">'.trimDurationText($file->duration).'</span>' : '')
							.'<picture><img src="'.$thumbnail.'" type="image/png" draggable="false"/></picture>'
						.'</div>' 

						.'<div class="d-flex flex-row justify-content-between w-100">
								<div class="d-flex flex-column m-2">
									<span class="preview-title" title="'.$file->name.'">'
									.	htmlspecialchars($file->name)
								.'</span>'
								. '<span>Format: '.$filetype.' &#8226; '
									.	$difference.'</span>'
								.'</div>'
								.'<div class="dropdown">'
									.'<div class="options" data-toggle="dropdown" aria-expanded="false">'
										.'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
												<path d="M12 16.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5
														1.5-1.5zM10.5 12c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5-.67-1.5-1.5-1.5-1.5.67-1.5
															1.5zm0-6c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5-.67-1.5-1.5-1.5-1.5.67-1.5 1.5z"></path>
											</svg>'
										.'</div>'
									.'<div class="d-flex flex-column justify-content-end">
											<ul class="dropdown-menu">'
												.(($file->is_shared == 0) ? 
												'<li><a class="dropdown-item share-btn">Share</a></li>
												 <li><a class="dropdown-item del-btn" rowId="'.$file->id.'">Delete</a></li>' : '') 
										. '<li><a class="dropdown-item" href="'.base_url('/Download'.$file->filetype.'?id='.$file->id).'">Download</a></li>'
										.	'</ul>'
									.'</div>'
								.'</div>'
						.'</div>'
					.'</div>';

}

