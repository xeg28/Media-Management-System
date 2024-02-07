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
		$sharedImgModel = new SharedImageModel();
        $data['title'] = 'Image';
        $data['showNavbar'] = true;
        $files['files'] = $imgModel->getAllByName();

        if(session('errors') !== null && !empty(session('errors'))) {
            $data['errors'] = session('errors');
        }

        echo view('templates/header', $data);
        echo view('content/sharepopup', $files);
        echo view('content/image', $files);
        echo view('templates/footer');
    }


    public function imageUpload()
    {
        // Set upload configuration
        helper(['form', 'url', 'upload', 'date']);

        $files = $this->request->getFileMultiple('file');
        $filenames = $this->request->getVar('name');
        $notes = $this->request->getVar('note');
        $targetPath = ROOTPATH . 'public/images/'; 

        foreach($files as $index => $file) {
            if($file->isValid() && !$file->hasMoved()) {
                $targetFile = $targetPath.$file->getName();
                $newName = rename_image(pathinfo($targetFile));
                $file->move($targetPath, $newName);
                $model = new ImageModel();
    
                $name = $filenames[$index];
                $filename = trim($name) === "" ? $file->getName() : $name;
                $note = $notes[$index];
        
                $imgData = [
                    'name' => $filename,
                    'type' => $file->getClientMimeType(),
                    'path' => $targetPath . $newName,
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
                $errors[] = "<li>You cannot share this image</li>";
				return redirect()->to(previous_url())->with('errors', $errors);
			}	
            
			$emails = $this->request->getVar("email");
			
			foreach($emails as $email) {
				$receiverId = $userModel->getUserIdByEmail($email);
				if($receiverId === null) {
                    $errors[] = "<li>Unable to send to ".$email."</li>";
					continue;
				}
                if($receiverId == $userId) {
                    $errors[] = "<li>You cannot send a file to yourself.</li>";
                    continue;
                }
				
				$sharedImgData = [
					'sender_id' => $userId,
					'receiver_id' => $receiverId,
					'image_id' => $imageId,
				];
				if($sharedImgModel->sharedImageExists($sharedImgData)) {
                    $errors[] = "<li>You already sent this file to ".$email."</li>";
                    continue;
                }
					$sharedImgModel->saveSharedImage($sharedImgData);
			}
			
            
            return redirect()->to(previous_url())->with('errors', $errors);
        }
	}

    public function delete() {
        helper(['form', 'url', 'upload']);
        $id = $this->request->getPost("id");
        $model = new ImageModel();
		$image = $model->getImage($id);
		if(!$image) {
			// return redirect()->to(previous_url());
            return $this->fail("Error deleting image", 400);
		}

        $path = $model->getImagePath($id);
        if(isset($path) && file_exists($path))
            unlink($path);
        
        $model->deleteImage($id);

        // return redirect()->to(previous_url());
        return $this->response->setJSON(['message' => 'AJAX request processed successfully']);
    }
}
