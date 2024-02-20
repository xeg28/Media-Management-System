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
  foreach( $files[$i] as $row ) { 
?>
<div class="share-popup">
  <div class="share-popup-content position-relative">
    <div class="card">
      <div class="card-header">
          <h5>Share <?=$row->name?></h5>
      </div>
      <span class="close-popup">&times;</span>
      <div class="card-body">
        <form action="<?= base_url('Share'.$row->filetype) ?>" method="post" class="form share-form">
          <div class="">
            <div class="d-flex justify-content-between">
              <label>User's Email</label>
              <div>
                <span class="add-share-input share-input-btn">&plus;</span>
                <span class="remove-share-input share-input-btn ">&minus;</span>
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
<?php $index++;}
  }?>