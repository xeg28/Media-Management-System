<?php

namespace App\Controllers;

use CodeIgniter\Files\File;
use App\Models\VideoModel;
use App\Models\UserModel;
use App\Models\SharedVideoModel;

class Video extends BaseController
{
    public function index()
    {
        $vidModel = new VideoModel();
        $sharedVidModel = new SharedVideoModel();
        $data = [];
        $data['title'] = 'Video';
        $data['showNavbar'] = true;
        $data['video'] = $vidModel->getAllByName();
        $data['sharedVideos'] = $sharedVidModel->getSharedVideos();

        if(session('errors') !== null && !empty(session('errors'))) {
            $data['errors'] = session('errors');
        }

        echo view('templates/header', $data);
        echo view('content/video', $data);
        echo view('templates/footer');
    }



    public function videoUpload()
    {
        // The upload helper is a custom helper that can be found in the helpers folder
        helper(['form', 'url', 'upload', 'date']);

        $files = $this->request->getFileMultiple('file');
        $filenames = $this->request->getVar('name');
        $notes = $this->request->getVar('note');
        $durations = $this->request->getVar('fileDuration');
        $targetPath = ROOTPATH . 'public/video/';

        foreach($files as $index => $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $targetFile = $targetPath . $file->getName();
                $newName = rename_video(pathinfo($targetFile));
                $file->move($targetPath, $newName);
                $model = new VideoModel();
    
                $durationInSeconds = $durations[$index];
                $time = get_time_from_seconds($durationInSeconds);
                $duration = sprintf('%02d:%02d:%02d', $time['hours'], $time['minutes'], $time['seconds']);
    
                $name = $filenames[$index];
                $filename = (trim($name) === "") ? $file->getName() : $name;
                $note = $notes[$index];
                
                $videoData = [    
                    'name' => $filename,
                    'type' => $file->getClientMimeType(),
                    'path' => $targetPath . $newName,
                    'caption' => $file->getName(),
                    'updated_at' => date('Y-m-d H:i:s', now()),
                    'duration' => $duration,
                    'note' => $note,
                    'user_id' => session()->get("id"),
                ];
    
                $model->saveVideo($videoData);
            }
        }
    }

    public function share() {
        if($this->request->getMethod() == 'post') {
            helper(['form', 'date']);
            $errors = [];
            $videoId = $this->request->getPost('id');
            $sharedVidModel = new SharedVideoModel();
            $vidModel = new VideoModel();
            $userModel = new UserModel();
            $userId = session()->get('id');
           
            $video = $vidModel->getVideo($videoId);
            if(!$video) {
                $errors[] = "<li>You cannot share this image.</li>";
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
               
                $sharedVidData = [
                    'sender_id' => $userId,
                    'receiver_id' => $receiverId,
                    'video_id' => $videoId,
                ];

                if($sharedVidModel->sharedVideoExists($sharedVidData)) {
                    $errors[] = "<li>You already sent this file to ".$email."</li>";     
                    continue;
                }
                    $sharedVidModel->saveSharedVideo($sharedVidData);
           }
            
           return redirect()->to(previous_url())->with('errors', $errors);
       }
   }

    public function videoDownload() {
        $model = new VideoModel();
        $id = $this->request->getVar('id');
        $path = $model->getVideoPath($id);

        return $this->response->download($path, null);
    }

    public function edit() {
        if($this->request->getMethod() == 'post') {
            helper(['form', 'date']);
			$id = $this->request->getPost('id');
            $model = new VideoModel();
            $video = $model->getVideo($id);
			
			if(!$video) {
                $errors[] = "<li>You can't edit this image.</li>";
				return redirect()->to(previous_url())->with('errors', $errors);
			}
			
            $vidData = [
                'id' => $id,
                'name' => $this->request->getPost('name'),
                'updated_at' => date('Y-m-d H:i:s', now()),
                'note' => $this->request->getPost('note'),
            ];

            $model->saveVideo($vidData);
            
            return redirect()->to(previous_url());
        }
    }

    public function delete() {
        helper(['form', 'url', 'upload']);
        $id = $this->request->getPost('id');
        $model = new VideoModel();
		
		$video = $model->getVideo($id);
		if(!$video) {
			return $this->fail("Error deleting video", 400);
		}
		
        $path = $model->getVideoPath($id);
        if(isset($path) && file_exists($path))
            unlink($path);
            
        $model->deleteVideo($id);

        return $this->response->setJSON(["message" => "Successfully deleted the video"]);
    }

}
