<?php

namespace App\Controllers;

use CodeIgniter\Files\File;
use App\Models\ImageModel;
use App\Models\SharedImageModel;
use App\Models\UserModel;
class Image extends BaseController
{
    public function index()
    {
        helper(["utility"]);
        $imgModel = new ImageModel();
        $data['title'] = 'Image';
        $data['showNavbar'] = true;
        $files['files'] = $imgModel->getAllByName();
        $files['imgPreview'] = (sizeof($files['files']) > 2) ? 'normal' : 'small';

        if(session('errors') !== null && !empty(session('errors'))) {
            $data['errors'] = session('errors');
        }

        echo view('templates/header', $data);
        echo view('content/sharepopup', $files);
        echo view('content/image', $files);
        echo view('templates/footer');
    }

    public function show($file_name)
    {
        if (!session()->get('isLoggedIn')) {
            $this->show_404('Page not found');
            return;
        }
        $testFile = $file_name;
        if(str_contains($file_name,'_thumb')) {
             $testFile= str_replace('_thumb', '', $file_name);
        }
        $imgModel = new ImageModel();
        $img_id = $imgModel->getIdByName($testFile);
        $img = $imgModel->getImage($img_id);
        if ($img) // validation
        {
            $file = UPLOADPATH. 'images/' . $file_name;
            $encodedFileName = rawurldecode($file_name);
            if (file_exists($file)) // check the file is existing 
            {
                $this->response->setHeader('X-Sendfile', 'images/'.$encodedFileName);
                $this->response->setHeader('Content-Type', $img->type);
                return $this->response;
            } else {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }
        else {
            $this->show_404('Page not found');
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }


    private function show_404($message)
    {
        $data= ['message' => $message];
        echo view('errors/html/error_404', $data);
    }


    public function imageUpload()
    {
        // Set upload configuration
        helper(['form', 'url', 'upload', 'date']);

        $files = $this->request->getFileMultiple('file');
        $filenames = $this->request->getVar('name');
        $notes = $this->request->getVar('note');
        $targetPath = UPLOADPATH . 'images/'; 

        foreach($files as $index => $file) {
            if($file->isValid() && !$file->hasMoved()) {
                $file->move($targetPath, null);
                $model = new ImageModel();

                $this->createThumbnail($targetPath . $file->getName());
    
                $name = $filenames[$index];
                $filename = trim($name) === "" ? $file->getName() : $name;
                $note = $notes[$index];
        
                $imgData = [
                    'name' => $filename,
                    'type' => $file->getClientMimeType(),
                    'path' => $targetPath . $file->getName(),
                    'caption' => $file->getName(),
                    'updated_at' => date('Y-m-d H:i:s', now()),
                    'note' => $note,
                    'user_id' => session()->get("id"),
                ];
    
                $model->saveImage($imgData);
            } 
        }
    }

    public function imageDownload() {
        $model = new ImageModel();
        $id = $this->request->getVar('id');
        $path = $model->getImagePath($id);

        return $this->response->download($path, null);
    }

    public function edit() {
        if($this->request->getMethod() == 'post') {
            helper(['form', 'date']);
            $errors = [];
			$id = $this->request->getPost('id');
            $model = new ImageModel();
            $sharedImgModel = new SharedImageModel();
			$image = $model->getImage($id);
			if(!$image) {
                $errors[] = "<li>You can't edit this image.</li>
                            <li>image id: ".$id."</li>
                            <li>note: ".$this->request->getPost('note')."</li>
                            <li>".$this->request->getPost('name')."</li>";
                return redirect()->to(previous_url())->with('errors', $errors);
			}	
            
            $imgData = [
                'id' => $id,
                'name' => $this->request->getPost('name'),
                'updated_at' => date('Y-m-d H:i:s', now()),
                'note' => $this->request->getPost('note'),
            ];

            $model->saveImage($imgData);
            
            return redirect()->to(previous_url());
        }
    }
	
	public function share() {
		 if($this->request->getMethod() == 'post') {
            helper(['form', 'date']);
			$imageId = $this->request->getPost('id');
            $errors = [];
            $sharedImgModel = new SharedImageModel();
			$imgModel = new ImageModel();
			$userModel = new UserModel();
			$userId = session()->get('id');
			
			$image = $imgModel->getImage($imageId);
			if(!$image) {
                $errors[] = "You cannot share this image";
				return redirect()->to(previous_url())->with('errors', $errors);
			}	
            
			$emails = $this->request->getVar("email");
			
			foreach($emails as $email) {
				$receiverId = $userModel->getUserIdByEmail($email);
				if($receiverId === null) {
                    $errors[] = "Unable to send to ".$email;
					continue;
				}
                if($receiverId == $userId) {
                    $errors[] = "You cannot send a file to yourself";
                    continue;
                }
				
				$sharedImgData = [
					'sender_id' => $userId,
					'receiver_id' => $receiverId,
					'image_id' => $imageId,
				];
				if($sharedImgModel->sharedImageExists($sharedImgData)) {
                    $errors[] = "You already sent this file to ".$email;
                    continue;
                }
					$sharedImgModel->saveSharedImage($sharedImgData);
			}
			
            
            return redirect()->to(previous_url())->with('errors', $errors);
        }
	}

    private function createThumbnail($file) {
        helper('utility');
        $image = \Config\Services::image();

        $originalImagePath = $file;
        $temp = explode('/', $file);
        $filename = nameOfFile(end($temp));

        $lowQualityImagePath = UPLOADPATH . '/images/' . $filename . '_thumb.'.pathinfo($file)['extension'];

        $targetWidth = 500;

        $image->withFile($originalImagePath);

        $originalWidth = $image->getWidth();
        
        $originalHeight = $image->getHeight();

        $newHeight = ($originalHeight / $originalWidth) * $targetWidth;

        $image->resize($targetWidth, $newHeight);

        $image->save($lowQualityImagePath);

        // $webpVersionPath =  UPLOADPATH . '/images/' . $filename . '_thumb.' . 'webp';
        // $image->convert(IMAGETYPE_WEBP);
        // $image->save($webpVersionPath);

        $image->clear();
    }

    public function delete() {
        helper(['form', 'url', 'upload', 'utility', 'render']);
        $id = $this->request->getPost("id");
        $model = new ImageModel();
		$image = $model->getImage($id);
		if(!$image) {
			// return redirect()->to(previous_url());
            return $this->fail("Error deleting image", 400);
		}

        $path = $model->getImagePath($id);
        $thumbPath =  UPLOADPATH . 'images/'. nameOfFile($image->caption) . '_thumb.' . pathinfo($path)['extension'];
        if(isset($path) && file_exists($path))
            unlink($path);
        if(file_exists($thumbPath)) {
            unlink($thumbPath);
        }
        
        
        $model->deleteImage($id);
        $images = $model->getLastTenUpdated();
        $image = null;
        if(sizeof($images) === 10 && previous_url() == base_url('/home')) {
            $image = $images[9];
        }

        $filePreview = createFilePreview($image);
        $sharePopup = createSharePopup($image);
        // return redirect()->to(previous_url());
        return $this->response->setJSON(['message' => 'AJAX request processed successfully', 
                                        'filePreview' => $filePreview,
                                        'sharePopup' => $sharePopup,
                                        'images' => $images]);
    }
}
