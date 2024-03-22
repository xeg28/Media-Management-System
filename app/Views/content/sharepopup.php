<!-- HTML for the share popup -->
<?php
$index = 0; 
$listOfFiles = ($title == "Home") ? [$images, $audio, $videos] : [(isset($files))? $files : null];
$files = (isset($file)) ? [[$file]] : $listOfFiles;
if(isset($related_files)) {
  foreach($related_files as $related_file) {
    array_push($files[0], $related_file);
  }
}

for ($i = 0; $i < sizeof($files); $i++) {
  foreach( $files[$i] as $index => $row ) { 
?>
<div class="share-popup" tabindex="0"
<?php if(isset($lastShare) && sizeof($files[$i]) == $index + 1) echo 'last'.$row->filetype.'="true"'?>>
  <div class="share-popup-content position-relative">
    <div class="card">
      <div class="card-header">
          <h5>Share <?=$row->name?></h5>
      </div>
      <button class="close-popup">
        <span tabindex="-1">&times;</span>
      </button>
     
      <div class="card-body">
        <form action="<?= base_url('Share'.$row->filetype) ?>" method="post" class="form share-form">
          <div class="">
            <div class="d-flex justify-content-between">
              <label>User's Email</label>
              <div>
                <button class="add-share-input share-input-btn">
                  <span tabindex="-1">&plus;</span>
                </button>
                <button class="remove-share-input share-input-btn">
                  <span tabindex="-1">&minus;</span>
                </button>
              </div>
            </div>
            <input type="hidden" name="id" value="<?= $row->id ?>">
            <div class="share-emails">
              <input type="text" class="form-control mb-2 field" name="email[]" placeholder="Enter an email">
            </div>
          </div>
        </form>
        <div class="row d-flex justify-content-center">
          <button class="btn btn-theme mt-4 mx-auto share-submit-btn">Share</button>
        </div>
      </div>
    </div>
  </div>
</div>
<?php }
  }?>