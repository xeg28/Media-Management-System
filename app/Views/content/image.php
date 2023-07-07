
<div class="container-fluid">
    <div class="row pt-5 justify-content-center">
        <div class="col-10">
            <div class="card">
                <div class="card-body center-div">
                    <form action="<?php echo base_url(); ?>/ImageUpload" method="post" class="dropzone w-100 mb-2" id="imageupload">
                    </form>
                    <div class="w-50" id="ImageUploadContainer">
                    </div>
                    <button class="upload-btn btn-theme">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($errors)) : ?>
        <div class="error-msg popup">
            <div class="popup-content">
                <div class="alert alert-danger" role="alert">
                    <span class="close-btn error-btn">&times;</span>
                    <div class="row pt-2 pb-2">
                        <?php foreach($errors as $error) {
                            echo $error;    
                        }?>
                    </div>
                </div>
            </div>
        </div>
        
    <?php endif;?>

    <div class="col-10 offset-1 pt-5 pb-5">
        <div class="card shadow">
            <div class="card-header d-flex align-items-center">
                <div class="col">
                    Image Files
                </div>
                <div class="ml-auto">
                    <a class="btn btn-small file-view" id="userimages">My Images</a>
                    <a class="btn btn-small file-view" id="sharedimages">Shared Images</a>
                </div>
            </div>

            
            <div class="card-body">
                <div class="card-title">
                    <div class="row border-bottom pb-2">
                        <div class="col-1"><strong>Icon</strong></div>
                        <div class="col-5"><strong>Name</strong></div>
                        <div class="col-4"><strong>Type</strong></div>
                    </div>
                </div>
                
                <div class="card-data-container shared-files">
                    <?php
                    $index = 0;
                    if (!empty($sharedImages)) {
                        foreach ($sharedImages as $row) {
                    ?>      
                        <div class="card-data">
                            <div class="row pb-2 align-items-center">
                                <div class="col-1"><embed src="<?php echo base_url('public/images/' . $row->caption); ?>" type="<?php echo $row->type; ?>" width="30px" height="30px" style="object-fit: contain;"/></div>
                                <div class="col-5">
                                    <a class="show-media link-primary" href="#" index="<?=$index?>"><?= htmlspecialchars($row->name) ?></a>
                                </div>
                                <div class="col-4"><?php echo $row->type ?></div>
                                <div class="col-2">
                                    
                                </div>
                            </div>
                        </div>
                        
                            <div class="media-popup" index="<?=$index?>">
                            <div class="media-popup-content">
                                <div class="card">
                                    <div class="card-header">
                                        <span class="close-popup">&times;</span>  
                                        <h5><?=htmlspecialchars($row->name)?></h5>                                         
                                    </div>
                                    <div class="card-body" style="max-height: 60%">
                                        <div class="embed-responsive">
                                            <img class="media" src="public/images/<?=$row->caption?>">
                                        </div>
                                        <hr>
                                        <h5>Description:</h5>
                                        <pre class="note"><strong>Shared by <?=$row->sender_email?> at <?=$row->shared_at?></strong>
                                        </br><?=htmlspecialchars($row->note)?></pre>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <?php $index++; }
                        } else { ?>
                        <p>No shared image(s) found...</p>
                        <?php }?>
                </div>
                
                <div class="card-data-container user-files">
                    <?php
                    if (!empty($images)) {
                        foreach ($images as $row) {
                    ?>      
                        <div class="card-data" index="<?=$index?>">
                            <div class="row pb-2 align-items-center">
                                <div class="col-1"><embed src="<?php echo base_url('public/images/' . $row->caption); ?>" type="<?php echo $row->type; ?>" width="30px" height="30px" style="object-fit: contain;"/></div>
                                <div class="col-5">
                                    <a class="show-media link-primary" href="#" index="<?=$index?>"><?= htmlspecialchars($row->name) ?></a>
                                </div>
                                <div class="col-4"><?php echo $row->type ?></div>
                                <div class="col-2 d-none d-lg-block">
                                    <a class="btn btn-primary btn-sm del-btn" rowId="<?=$row->id?>">Delete</a>
                                    <button class="btn btn-primary btn-sm edit-btn" index="<?=$index?>">Edit</button>
                                    <button class="btn btn-primary btn-sm share-btn" index="<?=$index?>">Share</button>
                                </div>
                                <div class="col-2 btn-group d-lg-none">
                                    <button type="button" class="btn btn-sm dropdown-toggle w-100" data-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item del-btn" rowId="<?=$row->id?>">Delete</a></li>
                                        <li><a class="dropdown-item edit-btn" href="#" index="<?=$index?>">Edit</a></li>
                                        <li><a class="dropdown-item share-btn" href="#" index="<?=$index?>">Share</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- HTML for the share popup -->
                        <div class="share-popup" index="<?=$index?>">
                            <div class="share-popup-content">
                                <div class="card">
                                    <div class="card-header">
                                        <span class="close-popup">&times;</span>
                                        <div class="row">
                                            <h5>Share Image</h5>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form action="<?=base_url('ShareImage')?>" method="post" class="form share-form">
                                            <div class="form-group">
                                                <label for="inputName">User's Email</label>
                                                <input type="hidden" name="id" value="<?=$row->id?>">
                                                <input type="text" class="form-control mb-2 field" name="email[]" placeholder="Enter an email">
                                                
                                            </div>
                                        </form>
                                        <div class="row d-flex justify-content-center"> 
                                            <button class="btn btn-theme mt-4 mx-auto add-share-input" index="<?=$index?>">Add</button>
                                            <button class="btn btn-theme mt-4 mx-auto share-submit-btn" index="<?=$index?>">Share</button>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div> 
                        
                        <!-- HTML for the image popup -->
                        <div class="media-popup" index="<?=$index?>">
                            <div class="media-popup-content">
                                <div class="card">
                                    <div class="card-header">
                                        <span class="close-popup">&times;</span>
                                        <h5><?=htmlspecialchars($row->name)?></h5>
                                    </div>
                                    <div class="card-body" style="max-height: 60%">
                                        <div class="embed-responsive">
                                            <img class="media" src="public/images/<?=$row->caption?>">
                                        </div>
                                        <hr>
                                        <h5>Description:</h5>
                                        <pre class="note"><?=htmlspecialchars($row->note)?></pre>
                                    </div>
                                </div>
                            </div>
                        </div> 

                        <!-- HTML for the edit popup -->
                        <div class="edit-popup" index="<?=$index?>">
                            <div class="edit-popup-content">
                                <div class="card">
                                    <div class="card-header">
                                        <span class="close-popup">&times;</span>
                                        <div class="row">
                                            <h5>Edit Image</h5>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form action="<?=base_url('EditImage')?>" method="post" class="form">
                                            <div class="form-group">
                                                <label for="inputName">Name</label>
                                                <input type="hidden" name="id" value="<?=$row->id?>">
                                                <input type="text" class="form-control mb-2 field" id="inputName" name="name" value="<?=htmlspecialchars($row->name)?>" placeholder="Name">
                                                
                                                <label for="inputNote">Description</label>
                                                <textarea class="form-control w-100 note" id="inputNote" name="note" wrap="hard" placeholder="Description"><?=htmlspecialchars($row->note)?></textarea>
                                                
                                                <div class="row d-flex justify-content-center"> 
                                                    <input type="submit" value="Save" class="btn-theme mt-4 mx-auto">
                                                </div>  
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                        $index++;
                        }
                    } else {
                        ?>
                        <p>No image(s) found...</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>


