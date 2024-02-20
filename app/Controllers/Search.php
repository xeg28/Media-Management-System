<?php

namespace App\Controllers;
use App\Models\ImageModel;
use App\Models\AudioModel;
use App\Models\VideoModel;
use App\Models\SharedImageModel;
use App\Models\SharedAudioModel;
use App\Models\SharedVideoModel;

class Search extends BaseController
{
    public function index()
    {
        helper(['utility', 'render']);
        $imgModel = new ImageModel();
        $audModel = new AudioModel();
        $vidModel = new VideoModel();

        $query = $this->request->getVar('query');

        $data['title'] = 'Search Results';
        $data['showNavbar'] = true; 
        $data['query'] = trim($query);
        $images = $imgModel->searchImages($query);
        $audios = $audModel->searchAudios($query);
        $videos = $vidModel->searchVideos($query);
        $data['files'] = array_merge($images, $audios, $videos);
        $data['preview'] = (sizeof($data['files'])) > 2 ? 'normal' : 'small';

        if(session('errors') !== null && !empty(session('errors'))) {
            $data['errors'] = session('errors');
        }
        
        echo view('templates/header', $data);
        echo view('content/sharepopup', $data);
        echo view('content/search', $data);
        echo view('templates/footer');
    }
}
