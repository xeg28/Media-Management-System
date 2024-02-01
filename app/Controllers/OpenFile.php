<?php

namespace App\Controllers;
use App\Models\ImageModel;
use App\Models\AudioModel;
use App\Models\VideoModel;


class OpenFile extends BaseController
{
    // instead of using index, create other functions and routes for specific file type
    public function openImage() {
        helper(['utility']);
        $imgModel = new ImageModel();
        $audModel = new AudioModel();
        $vidModel = new VideoModel();
        $id = $this->request->getVar('id');
        $image = $imgModel->getImage($id);

        if (!$image) {
            $data['errors'] = 'You do not have access to this file';
            return redirect()->to('/');
        }

        $data['title'] = $image->name;
        $data['file'] = $image;
        $data['showNavbar'] = true;
        $data['images'] = $imgModel->getLastTenUpdated();
        $data['audio'] = $audModel->getLastTenUpdated();
        $data['videos'] = $vidModel->getLastTenUpdated();

        if(session('errors') !== null && !empty(session('errors'))) {
            $data['errors'] = session('errors');
        }
        // when getting the file from the database, check if the file belongs to the user in session.
        // If it doesn't belong to the user, check if the file is shared first and if it is then you can display.
        // Create a column in the database called is_shared and if the file belongs to the user make it true and false otherwise.
        echo view('templates/header', $data);
        echo view('content/openfile', $data);
        echo view('content/sharepopup', $data);
        echo view('templates/footer');
    }

    public function openVideo() {
        helper(['utility']);
        $imgModel = new ImageModel();
        $audModel = new AudioModel();
        $vidModel = new VideoModel();
        $id = $this->request->getVar('id');
        $video = $vidModel->getVideo($id);

        if (!$video) {
            $data['errors'] = 'You do not have access to this file';
            return redirect()->to('/');
        }

        $data['title'] = $video->name;
        $data['file'] = $video;
        $data['showNavbar'] = true;
        $data['images'] = $imgModel->getLastTenUpdated();
        $data['audio'] = $audModel->getLastTenUpdated();
        $data['videos'] = $vidModel->getLastTenUpdated();

        if(session('errors') !== null && !empty(session('errors'))) {
            $data['errors'] = session('errors');
        }
        // when getting the file from the database, check if the file belongs to the user in session.
        // If it doesn't belong to the user, check if the file is shared first and if it is then you can display.
        // Create a column in the database called is_shared and if the file belongs to the user make it true and false otherwise.
        echo view('templates/header', $data);
        echo view('content/openfile', $data);
        echo view('content/sharepopup', $data);
        echo view('templates/footer');
    }

    public function openAudio() {
        helper(['utility']);
        $imgModel = new ImageModel();
        $audModel = new AudioModel();
        $vidModel = new VideoModel();
        $id = $this->request->getVar('id');
        $audio = $audModel->getAudio($id);

        if (!$audio) {
            $data['errors'] = 'You do not have access to this file';
            return redirect()->to('/');
        }

        $data['title'] = $audio->name;
        $data['file'] = $audio;
        $data['showNavbar'] = true;
        $data['images'] = $imgModel->getLastTenUpdated();
        $data['audio'] = $audModel->getLastTenUpdated();
        $data['videos'] = $vidModel->getLastTenUpdated();

        if(session('errors') !== null && !empty(session('errors'))) {
            $data['errors'] = session('errors');
        }
        // when getting the file from the database, check if the file belongs to the user in session.
        // If it doesn't belong to the user, check if the file is shared first and if it is then you can display.
        // Create a column in the database called is_shared and if the file belongs to the user make it true and false otherwise.
        echo view('templates/header', $data);
        echo view('content/openfile', $data);
        echo view('content/sharepopup', $data);
        echo view('templates/footer');
    }
}
