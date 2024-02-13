<?php

namespace App\Controllers;

use CodeIgniter\Files\File;
use App\Models\AudioModel;
use App\Models\UserModel;
use App\Models\SharedAudioModel;

class Audio extends BaseController
{
    public function __construct() {

    }

    public function index()
    {
        helper(["utility"]);
        $audModel = new AudioModel();
		$sharedAudModel = new SharedAudioModel();
        $data = [];
        $data['title'] = 'Audio';
        $data['showNavbar'] = true;
        $files['files'] = $audModel->getAllByName();
		$data['sharedAudios'] = $sharedAudModel->getSharedAudios();
        $files['audPreview'] = (sizeof($files['files']) > 2) ? 'normal' : 'small';

        if(session('errors') !== null && !empty(session('errors'))) {
            $data['errors'] = session('errors');
        }

        echo view('templates/header', $data);
        echo view('content/sharepopup', $files);
        echo view('content/audio', $files);
        echo view('templates/footer');
    }

    public function show($file_name) {
        if(!session()->get('isLoggedIn')) {
            $this->show_404('Page not found');
            return;
        }
        $audModel = new AudioModel();
        $audio_id = $audModel->getIdByName($file_name);

        if($audio_id == null) {
            $this->show_404('File not found');
            return;
        }

        $audio = $audModel->getAudio($audio_id);
        if($audio) {
            $file = UPLOADPATH . 'audios/'. $file_name;
            if(file_exists($file)) {
                $this->response->setHeader('X-Sendfile', 'audios/'.$file_name);
                $this->response->setHeader('Content-Type', $audio->type);
                return $this->response;
            }
            else {
                $this->show_404('File not found');
            }
        }
        else {
            $this->show_404('Page not found');
        }
    }

    private function show_404($message) {
        $data['message'] = $message;
        echo view('errors/html/error_404', $data);
    }



    public function audioUpload()
    {
        // The upload helper is a custom helper that can be found in the helpers folder
        helper(['form', 'url', 'upload', 'date']);

        $files = $this->request->getFileMultiple('file');
        $filenames = $this->request->getVar('name');
        $notes = $this->request->getVar('note');
        $durations = $this->request->getVar('fileDuration');
        $targetPath = UPLOADPATH . 'audios/';

        foreach($files as $index => $file) {
            if($file->isValid() && !$file->hasMoved()) {
                $file->move($targetPath, null);
                $model = new AudioModel();
                
                $durationInSeconds = $durations[$index];
                $time = get_time_from_seconds($durationInSeconds);
                $duration = sprintf('%02d:%02d:%02d', $time['hours'], $time['minutes'], $time['seconds']);
                
                $name = $filenames[$index];
                $filename = trim($name) === "" ? $file->getName() : $name;
                $note = $notes[$index];
    
                $audioData = [
                    'name' => $filename,
                    'type' => $file->getClientMimeType(),
                    'path' => $targetPath . $file->getName(),
                    'caption' => $file->getName(),
                    'updated_at' => date('Y-m-d H:i:s', now()),
                    'duration' => $duration,
                    'note' => $note,
                    'user_id' => session()->get("id"),
                ];
    
                $model->saveAudio($audioData); 
            }
        }
    }
	
	public function share() {
		 if($this->request->getMethod() == 'post') {
            helper(['form', 'date']);
            $errors = [];
			$AudioId = $this->request->getPost('id');
            $sharedAudModel = new SharedAudioModel();
			$audModel = new AudioModel();
			$userModel = new UserModel();
			$userId = session()->get('id');
			
			$audio = $audModel->getAudio($AudioId);
			if(!$audio) {
                $errors[] = "<li>You cannot share this file</li>";
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
                    $errors[] = "<li>You cannot send a file to yourself</li>";
                    continue;
                }
				
				$sharedAudData = [
					'sender_id' => $userId,
					'receiver_id' => $receiverId,
					'audio_id' => $AudioId,
				];

				if($sharedAudModel->sharedAudioExists($sharedAudData)) {
                    $errors[] = "<li>You already sent this file to ".$email."</li>";
                    continue; 
                }
					$sharedAudModel->saveSharedAudio($sharedAudData);
			}	
            return redirect()->to(previous_url())->with('errors', $errors);
        }
	}

    public function audioDownload() {
        $model = new AudioModel();
        $id = $this->request->getVar('id');
        $path = $model->getAudioPath($id);

        return $this->response->download($path, null);
    }

    public function edit() {
        if($this->request->getMethod() == 'post') {
            helper(['form', 'date']);
			$id = $this->request->getPost('id');
            $model = new AudioModel();
			
            $audio = $model->getAudio($id);
			if(!$audio) {
                $errors[] = "<li>You cannot edit this file</li>"; 
				return redirect()->to(previous_url())->with('errors', $errors);
			}
			
            $audData = [
                'id' => $id,
                'name' => $this->request->getPost('name'),
                'updated_at' => date('Y-m-d H:i:s', now()),
                'note' => $this->request->getPost('note'),
            ];

            $model->saveAudio($audData);
            
            return redirect()->to(previous_url());
        }
    }


    public function delete() {
        helper(['form', 'url', 'upload']);
        $id = $this->request->getPost("id");
        $model = new AudioModel();
		
	    $audio = $model->getAudio($id);
		if(!$audio) {
			return $this->fail("Error deleting audio", 400);
		}
		
        $path = $model->getAudioPath($id);
        if(isset($path) && file_exists($path))
            unlink($path);
            
        $model->deleteAudio($id);

        return $this->response->setJSON(['message' => 'AJAX request processed successfully']);
    }
}
