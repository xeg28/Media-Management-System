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
        $imgModel = new ImageModel();
        $audModel = new AudioModel();
        $vidModel = new VideoModel();

        $sharedImgModel = new SharedImageModel();
        $sharedAudModel = new SharedAudioModel();
        $sharedVidModel = new SharedVideoModel();

        $query = $this->request->getVar('query');

        $data['title'] = 'Search Results';
        $data['showNavbar'] = true; 
        $data['query'] = trim($query);
        $images = $imgModel->searchImages($query);
        $audios = $audModel->searchAudios($query);
        $videos = $vidModel->searchVideos($query);
        $data['files'] = array_merge($images, $audios, $videos);
        
        $sharedImages = $sharedImgModel->searchSharedImages($query);
        $sharedAudios = $sharedAudModel->searchSharedAudios($query);
        $sharedVideos = $sharedVidModel->searchSharedVideos($query);

        $data['sharedFiles'] = array_merge($sharedImages, $sharedAudios, $sharedVideos);

        if(session('errors') !== null && !empty(session('errors'))) {
            $data['errors'] = session('errors');
        }
        
        echo view('templates/header', $data);
        echo view('content/search', $data);
        echo view('templates/footer');
    }
}
