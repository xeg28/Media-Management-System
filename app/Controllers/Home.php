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
        helper(['utility']);
        $imgModel = new ImageModel();
        $audModel = new AudioModel();
        $vidModel = new VideoModel();
        $data['title'] = 'Home';
        $data['showNavbar'] = true;
        $data['images'] = $imgModel->getLastTenUpdated();
        $data['audio'] = $audModel->getLastTenUpdated();
        $data['videos'] = $vidModel->getLastTenUpdated();
        $data['lastShare'] = true;
        $data['imgPreview'] = (sizeof($data['images']) > 2) ? 'normal' : 'small';
        $data['vidPreview'] = (sizeof($data['videos']) > 2) ? 'normal' : 'small';
        $data['audPreview'] = (sizeof($data['audio']) > 2) ? 'normal' : 'small';

        if(session('errors') !== null && !empty(session('errors'))) {
            $data['errors'] = session('errors');
        }

        echo view('templates/header', $data);
        echo view('content/home', $data);
        echo view('content/sharepopup', $data);
        echo view('templates/footer');
    }
}
