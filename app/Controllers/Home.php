<?php

namespace App\Controllers;
use App\Models\ImageModel;
use App\Models\AudioModel;
use App\Models\VideoModel;
use App\Models\SharedImageModel;
use App\Models\SharedAudioModel;
use App\Models\SharedVideoModel;

class Home extends BaseController
{
    public function index()
    {
        $imgModel = new ImageModel();
        $audModel = new AudioModel();
        $vidModel = new VideoModel();
        $sharedImgModel = new SharedImageModel();
        $sharedAudModel = new SharedAudioModel();
        $sharedVidModel = new SharedVideoModel();
        $data['title'] = 'Home';
        $data['showNavbar'] = true;
        $data['images'] = $imgModel->getLastTenUpdated();
        $data['audio'] = $audModel->getLastTenUpdated();
        $data['videos'] = $vidModel->getLastTenUpdated();
        $data['sharedImages'] = $sharedImgModel->getLastTenSharedImages();
        $data['sharedAudios'] = $sharedAudModel->getLastTenSharedAudios();
        $data['sharedVideos'] = $sharedVidModel->getLastTenSharedVideos();

        if(session('errors') !== null && !empty(session('errors'))) {
            $data['errors'] = session('errors');
        }

        echo view('templates/header', $data);
        echo view('content/home', $data);
        echo view('templates/footer');
    }
}
