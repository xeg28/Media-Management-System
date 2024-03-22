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
        helper(["utility", "render"]);
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
			$image = $model->getImage($id);
			if(!$image) {
                $errors[] = "<li>You can't edit this image.</li>
                            <li>image id: ".$id."</li>
                            <li>note: ".$this->request->getPost('note')."</li>
                            <li>".$this->request->getPost('name')."</li>";
                return redirect()->to(previous_url())->with('errors', $errors);
			}	

            $note = $this->request->getPost('note');
            
            $imgData = [
                'id' => $id,
                'name' => $this->request->getPost('name'),
                'updated_at' => date('Y-m-d H:i:s', now()),
                'note' => $note,
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
