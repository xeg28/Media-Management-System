<?php

namespace App\Controllers;

use App\Models\ImageModel;
use App\Models\AudioModel;
use App\Models\VideoModel;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;

class Upload extends BaseController
{
  public function index()
  {
    $data['title'] = 'Upload';
    $data['showNavbar'] = 'true';
    echo view('templates/header', $data);
    echo view('content/upload', $data);
    echo view('templates/footer');
  }

  public function fileUpload()
  {
    $files = $this->request->getFileMultiple('file');
    $filenames = $this->request->getVar('name');
    $notes = $this->request->getVar('note');
    $durations = json_decode($this->request->getVar('durations'), true);
    $uuid = $this->request->getVar('uuid');
    $idIndex = 0;
    foreach ($files as $index => $file) {
      $mimeType = $file->getClientMimeType();
      $type = explode('/', $mimeType)[0];
      $extension = explode('/', $mimeType)[1];
      $uploadData = [
        'file' => $file,
        'filename' => $filenames[$index],
        'note' => $notes[$index]
      ];

      if ($type == 'image') {
        $this->imageUpload($uploadData);
      } else if ($type == 'audio') {
        $uploadData['duration'] = $durations[$uuid[$idIndex++]];
        $this->audioUpload($uploadData);
      } else if ($extension == 'webm') {
        if ($this->webmContainsVideo($file->getRealPath())) {
          //download a webm with video to test out
          //needs videoUpload function here
          $uploadData['duration'] = $durations[$uuid[$index]];
          $this->videoUpload($uploadData);
        } else {
          $uploadData['duration'] = $durations[$uuid[$index]];
          $this->audioUpload($uploadData);
        }
      } else if ($type == 'video') {
        $uploadData['duration'] = $durations[$uuid[$index]];
        $this->videoUpload($uploadData);
      }
    }

  }

  private function imageUpload($uploadData)
  {
    // Set upload configuration
    helper(['form', 'url', 'upload', 'date']);

    $targetPath = UPLOADPATH . 'images/';
    $file = $uploadData['file'];

    if ($file->isValid() && !$file->hasMoved()) {
      $file->move($targetPath, null);
      $model = new ImageModel();

      $this->createThumbnail($targetPath . $file->getName());

      $name = $uploadData['filename'];
      $filename = trim($name) === "" ? $file->getName() : $name;
      $note = $uploadData['note'];

      $imgData = [
        'name' => $filename,
        'type' => $file->getClientMimeType(),
        'path' => $targetPath . $file->getName(),
        'caption' => $file->getName(),
        'updated_at' => date('Y-m-d H:i:s', now()),
        'note' => $note,
        'user_id' => session()->get("id"),
      ];

      $model->saveImage($imgData);
    }
  }

  private function audioUpload($uploadData)
  {
    // The upload helper is a custom helper that can be found in the helpers folder
    helper(['form', 'url', 'upload', 'date']);

    $file = $uploadData['file'];
    $targetPath = UPLOADPATH . 'audios/';

    if ($file->isValid() && !$file->hasMoved()) {
      $file->move($targetPath, null);
      $model = new AudioModel();

      $durationInSeconds = $uploadData['duration'];
      $time = get_time_from_seconds($durationInSeconds);
      $duration = sprintf('%02d:%02d:%02d', $time['hours'], $time['minutes'], $time['seconds']);

      $name = $uploadData['filename'];
      $filename = trim($name) === "" ? $file->getName() : $name;
      $note = $uploadData['note'];

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

  public function videoUpload($uploadData)
  {
    // The upload helper is a custom helper that can be found in the helpers folder
    helper(['form', 'url', 'upload', 'date']);

    $file = $uploadData['file'];
    $targetPath = ROOTPATH . 'writable/uploads/videos/';


    if ($file->isValid() && !$file->hasMoved()) {
      $file->move($targetPath, null);
      $thumbnail = ROOTPATH . 'public/video/' . '/thumbnails/' . explode('.', $file->getName())[0] . '.png';
      $model = new VideoModel();

      $ffmpeg = FFMpeg::create([
        'ffmpeg.binaries' => 'app\ffmpeg\bin\ffmpeg.exe',
        'ffprobe.binaries' => 'app\ffmpeg\bin\ffprobe.exe'
      ]);

      $video = $ffmpeg->open($targetPath . $file->getName());
      $frame = $video->frame(TimeCode::fromSeconds(1));
      $frame->save($thumbnail);

      $durationInSeconds = $uploadData['duration'];
      $time = get_time_from_seconds($durationInSeconds);
      $duration = sprintf('%02d:%02d:%02d', $time['hours'], $time['minutes'], $time['seconds']);

      $name = $uploadData['filename'];
      $filename = (trim($name) === "") ? $file->getName() : $name;
      $note = $uploadData['note'];

      $videoData = [
        'name' => $filename,
        'type' => $file->getClientMimeType(),
        'path' => $targetPath . $file->getName(),
        'caption' => $file->getName(),
        'updated_at' => date('Y-m-d H:i:s', now()),
        'duration' => $duration,
        'note' => $note,
        'user_id' => session()->get("id"),
      ];

      $model->saveVideo($videoData);
    }

  }

  function webmContainsVideo($file_path)
  {

    $ffmpeg = FFMpeg::create([
      'ffmpeg.binaries' => 'app\ffmpeg\bin\ffmpeg.exe',
      'ffprobe.binaries' => 'app\ffmpeg\bin\ffprobe.exe',
    ]);

    $video = $ffmpeg->open($file_path);

    $streams = $video->getStreams();

    $videoStreamFound = false;

    foreach ($streams as $stream) {
      if ($stream->get('codec_type') === 'video') {
        $videoStreamFound = true;
        break;
      }
    }
    return $videoStreamFound;
  }
  private function createThumbnail($file)
  {
    helper('utility');
    $image = \Config\Services::image();

    $originalImagePath = $file;
    $temp = explode('/', $file);
    $filename = nameOfFile(end($temp));

    $lowQualityImagePath = UPLOADPATH . '/images/' . $filename . '_thumb.' . pathinfo($file)['extension'];

    $targetWidth = 500;

    $image->withFile($originalImagePath);

    $originalWidth = $image->getWidth();

    $originalHeight = $image->getHeight();

    $newHeight = ($originalHeight / $originalWidth) * $targetWidth;

    $image->resize($targetWidth, $newHeight);

    $image->save($lowQualityImagePath);

    // $webpVersionPath =  UPLOADPATH . '/images/' . $filename . '_thumb.' . 'webp';
    // $image->convert(IMAGETYPE_WEBP);
    // $image->save($webpVersionPath);

    $image->clear();
  }
}


