<!-- I want the dropzone to take up most of the screen and I want the text larger
     When A file is dropped into the dropzone, scroll to the file options -->
<div class="container-fluid">
  <div class="wrapper mt-3">
  <div class="d-flex justify-content-center m-0">
      <div class="card" id="upload">
        <div class="card-body center-div">
          <form action="<?php echo base_url(); ?>/FileUpload" method="post" class="dropzone w-100 mb-2"
            id="fileupload">
          </form>
          <div class="w-100" id="UploadContainer">
          </div>
          <button class="upload-btn btn-theme hide">Upload</button>
        </div>
      </div>
    </div>
  </div>
</div>