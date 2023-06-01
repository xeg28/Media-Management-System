<?php

namespace App\Controllers;

use CodeIgniter\Files\File;
use App\Models\ImageModel;

class Image extends BaseController
{

    public function index()
    {
        $model = new ImageModel();
        $data = [];
        $data['title'] = 'Image';
        $data['showNavbar'] = true;
        $files['images'] = $model->getAllByName() ;
        echo view('templates/header', $data);
        echo view('content/image', $files);
        echo view('templates/footer');
    }



    public function imageUpload()
    {
        // Set upload configuration
        helper(['form', 'url', 'upload', 'date']);

        $file = $this->request->getFile('file');
        $targetPath = ROOTPATH . 'public/images/'; 

        if($file->isValid() && !$file->hasMoved()) {
            $targetFile = $targetPath.$file->getName();
            $newName = rename_image(pathinfo($targetFile));
            $file->move($targetPath, $newName);
            $model = new ImageModel();

            $name = $this->request->getPost("name");
            $filename = trim($name) === "" ? $file->getName() : $name;
            $note = $this->request->getPost("note");
    
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

    public function imageDownload() {
        $model = new ImageModel();
        $id = $this->request->getVar('id');
        $path = $model->getImagePath($id);

        return $this->response->download($path, null);
    }

    public function edit() {
        if($this->request->getMethod() == 'post') {
            helper(['form', 'date']);
			$id = $this->request->getPost('id');
            $model = new ImageModel();
			$image = $model->getImage($id);
			if(!$image) {
				return redirect()->to(previous_url());
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

    public function delete($id) {
        helper(['form', 'url', 'upload']);
        $model = new ImageModel();
		$image = $model->getImage($id);
		if(!$image) {
			return redirect()->to(previous_url());
		}

        $path = $model->getImagePath($id);
        if(isset($path) && file_exists($path))
            unlink($path);
        
        $model->deleteImage($id);

        return redirect()->to(previous_url());
    }
}
