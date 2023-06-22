<section class="vh-100" id="home">
    <div class="container-fluid">
        <div class="row pt-5 justify-content-center">
			<div class="col-10">
				<div class="card">
					<div class="card-body center-div">
						<form action="<?php echo base_url(); ?>/AudioUpload" method="post" class="dropzone w-100 mb-2" id="audioupload">
						</form>
                        <div class="w-50" id="AudioUploadContainer">
                        </div>
                        <button class="upload-btn btn-green">Upload</button>	
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
            <div class="card">
               <div class="card-header d-flex align-items-center">
					<div class="col">
						Audio Files
					</div>
					<div class="ml-auto">
						<a class="btn btn-small file-view navbar-light" id="useraudios">My Audios</a>
						<a class="btn btn-small file-view" id="sharedaudios">Shared Audios</a>
					</div>
				</div>
                <div class="card-body">
                    <div class="card-title">
                        <div class="row border-bottom pb-2">
                            <div class="col-1"><strong>Icon</strong></div>
                            <div class="col-5"><strong>Name</strong></div>
                            <div class="col-2"><strong>Duration</strong></div>
                            <div class="col-2"><strong>Type</strong></div>
                            
                        </div>
                    </div>
					
					<div class="card-data-container shared-files">
                        <?php
						$index = 0;
                        if (!empty($sharedAudios)) {
                            foreach ($sharedAudios as $row) {
                        ?>      
                            <div class="card-data">
                                <div class="row pb-2 align-items-center">
                                    <div class="col-1"><embed src="<?php echo base_url('public/audio/icon.png'); ?>" type="image/png" width="30px" height="30px" /></div>
                                    <div class="col-5">
                                        <a class="show-media link-primary" href="#" id="<?=$index?>"><?= htmlspecialchars($row->name) ?></a>
                                    </div>
                                    <div class="col-2"><?php echo $row->duration ?></div>
                                    <div class="col-2"><?=$row->type?></div>
                                </div>
                            </div>
							
							 <div class="media-popup" id="media-popup-<?=$index?>">
                                <div class="media-popup-content">
                                    <div class="card">
                                        <div class="card-header">
                                            <span class="close-popup" index="<?=$index?>">&times;</span>
                                            <h5><?=htmlspecialchars($row->name)?></h5>
                                        </div>
                                        <div class="card-body" style="max-height: 60%">
                                            <div class="embed-responsive">
                                                <audio class="w-100" id="media<?=$index?>" controls><source src="public/audio/<?=$row->caption?>" type="<?=$row->type?>"></audio>
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
							<p>No shared audio(s) found...</p>
							<?php }?>
					</div>
					
                    <div class="card-data-container user-files">
                        <?php
                        if (!empty($audio)) {
                            foreach ($audio as $row) {
                        ?>  
                            <div class="card-data">
                                <div class="row pb-2 align-items-center">
                                    <div class="col-1"><embed src="<?php echo base_url('public/audio/icon.png'); ?>" type="image/png" width="30px" height="30px" /></div>
                                    <div class="col-5">
                                        <a class="show-media link-primary" id="<?=$index?>" href="#"><?=htmlspecialchars($row->name)?></a> 
                                    </div>
                                    <div class="col-2"><?=$row->duration?></div>
                                    <div class="col-2"><?php echo $row->type ?></div>
                                    <div class="col-2 d-none d-lg-block">
                                        <a class ="btn btn-sm btn-primary" href="<?=base_url('Audio/delete/'.$row->id)?>">Delete</a>
                                        <button class="btn btn-sm btn-primary edit-btn" index="<?=$index?>">Edit</button>
										<button class="btn btn-primary btn-sm share-btn" index="<?=$index?>">Share</button>
                                    </div>

                                    <div class="col-2 btn-group d-lg-none">
                                        <button type="button" class="btn btn-sm dropdown-toggle w-100" data-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="<?=base_url('/Audio/delete/'.$row->id)?>">Delete</a></li>
                                            <li><a class="dropdown-item edit-btn" href="#" index="<?=$index?>">Edit</a></li>
                                            <li><a class="dropdown-item share-btn" href="#" index="<?=$index?>">Share</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

							<!-- HTML for the share popup -->
							<div class="share-popup" id="share-popup" index="<?=$index?>">
                                <div class="share-popup-content">
									<div class="card">
                                        <div class="card-header">
                                            <span class="close-popup">&times;</span>
                                            <div class="row">
                                                <h5>Share Audio</h5>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <form action="<?=base_url('ShareAudio')?>" method="post" class="form share-form">
                                                <div class="form-group">
                                                    <label for="inputName">User's Email</label>
                                                    <input type="hidden" name="id" value="<?=$row->id?>">
                                                    <input type="text" class="form-control mb-2 field" name="email[]" placeholder="Enter an email">
                                                </div>
                                            </form>
											<div class="row d-flex justify-content-center"> 
												<button class="btn btn-green mt-4 mx-auto add-share-input" index="<?=$index?>">Add</button>
                                                <button class="btn btn-green mt-4 mx-auto share-submit-btn" index="<?=$index?>">Share</button>
											</div>  
                                        </div>
                                    </div>
                                </div>
                            </div> 

                            <!-- HTML for the audio player popup -->
                            <div class="media-popup" id="media-popup-<?=$index?>">
                                <div class="media-popup-content">
                                    <div class="card">
                                        <div class="card-header">
                                            <span class="close-popup" index="<?=$index?>" >&times;</span>
                                            <h5><?=htmlspecialchars($row->name)?></h5>
                                        </div>
                                        <div class="card-body">
                                            <audio class="w-100" id="media<?=$index?>" controls><source src="public/audio/<?=$row->caption?>" type="<?=$row->type?>"></audio>
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
                                            <span class="close-popup" index="<?=$index?>">&times;</span>
                                            <div class="row">
                                                <h5>Edit Audio</h5>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <form action="<?=base_url('/EditAudio')?>" method="post" class="form">
                                                <div class="form-group">
                                                    <label for="inputName">Name</label>
                                                    <input type="hidden" name="id" value="<?=$row->id?>">
                                                    <input type="text" class="form-control field mb-2" id="inputName" name="name" value="<?=htmlspecialchars($row->name)?>" placeholder="Name">
                                                    
                                                    <label for="inputNote">Description</label>
                                                    <textarea class="form-control w-100 note" id="inputNote" name="note" wrap="hard" placeholder="Description"><?=htmlspecialchars($row->note)?></textarea>

                                                    <div class="row d-flex justify-content-center"> 
                                                        <input type="submit" value="Save" class="btn btn-info btn-green mt-4 mx-auto">
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
                            <p>No audio(s) found...</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

