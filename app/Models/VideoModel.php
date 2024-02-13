<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\SharedVideoModel;

class VideoModel extends Model {
    protected $table = 'videos';
    protected $allowedFields = ['id', 'name', 'type', 'path', 'caption', 
                                'updated_at', 'duration', 'note', 'user_id'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $db;

    public function __construct() {
        $this->db = db_connect();
    }

    public function getIdByName($file_name) {
        $video_id = $this->db->table($this->table)
            ->select('id')
            ->getWhere(['caption' => $file_name])
            ->getRow();
        return $video_id->id;
    }

    public function getVideo($id) {
        parent::__construct();
        $user_id = session()->get("id");
        $video = $this->table($this->table)
                ->select("videos.*, 0 as 'is_shared', 'Video' as 'filetype'")
                ->getWhere(['id' => $id, 'user_id' => $user_id])
                ->getRow();

        $shared_model= new SharedVideoModel();
        if(!$video) {
            return $shared_model->getSharedVideo($id);
        }
        return $video;
    }

    public function saveVideo($video) {
        $this->save($video);
    }

    public function getLastTenUpdated() {
		$user_id = session()->get("id");
        $builder1 = $this->db->table($this->table)
                    ->select($this->table.".*, 1 as 'is_shared', users.email as 'sender_email', 'Video' as 'filetype'")
                    ->join('shared_'.$this->table, $this->table.'.id = shared_'.$this->table.'.video_id')
                    ->join('users', 'users.id = shared_'.$this->table.'.sender_id')
                    ->where('shared_'.$this->table.'.receiver_id', $user_id);
        $builder2 = $this->db->table($this->table)
                    ->select("*, 0 as 'is_shared', 0 as 'sender_email', 'Video' as 'filetype'")
                    ->where("user_id", $user_id);
        $builder1->union($builder2);

        $builder =$this->db->table('(' . $builder1->getCompiledSelect(false) . ') AS union_result');
        $builder->limit(10)
                ->orderBy('uploaded_at','DESC');
        $query = $builder->get();
        return $query->getResult();
    }

    public function getAllByName() {
        $user_id = session()->get("id");
        $builder1 = $this->db->table($this->table)
                    ->select($this->table.".*, 1 as 'is_shared', users.email as 'sender_email', 'Video' as 'filetype'")
                    ->join('shared_'.$this->table, $this->table.'.id = shared_'.$this->table.'.video_id')
                    ->join('users', 'users.id = shared_'.$this->table.'.sender_id')
                    ->where('shared_'.$this->table.'.receiver_id', $user_id);
        $builder2 = $this->db->table($this->table)
                    ->select("*, 0 as 'is_shared', 0 as 'sender_email', 'Video' as 'filetype'")
                    ->where("user_id", $user_id);
        $builder1->union($builder2);

        $builder =$this->db->table('(' . $builder1->getCompiledSelect(false) . ') AS union_result');
        $builder->orderBy('name','ASC');
        $query = $builder->get();
        return $query->getResult();
    }

    public function getVideoQuery($query) {
        $result = $this->db->query($query);
        return $result->getResult();
    }

    public function deleteVideo($id) {
        $sharedVidModel = new SharedVideoModel();
		$sharedVidModel->deleteByVideoId($id);
        $this->delete($id);
    } 

    public function getVideoPath($id) {
        parent::__construct();

        $video = $this->where('id', $id)->first();
        return (isset($video)) ? $video['path'] : null;
    }

    public function searchVideos($query) {
        $query = $this->db->escapeString($query);
		$user_id = session()->get('id');
		$builder1 = $this->db->table($this->table)
					->select("videos.*, 1 as is_shared, users.email as sender_email,
                            'Video' as filetype")
					->join('shared_videos', 'videos.id = shared_videos.video_id')
                    ->join('users', 'users.id = shared_videos.sender_id')
                    ->where('shared_videos.receiver_id', $user_id);
        
        $builder2 = $this->db->table($this->table)
                    ->select("*, 0 as is_shared, 0 as sender_email, 
                             'Video' as filetype")
                    ->where('user_id', $user_id);

        
        $builder1->union($builder2);
        $builder =$this->db->table('(' . $builder1->getCompiledSelect(false) . ') AS union_result');
        $words = null;
        if(str_contains($query, " ")) {
            $words = explode(" ", $query);
        }

		$builder->groupStart()->like('name', $query);

        if(!empty($words)) {
            foreach($words as $word) {
                if(trim($word) == '') {
                    continue;
                }    
				$builder->orLike('name', $word);
            }
        }

		$builder->groupEnd();

        $result = $builder->get();

        return $result->getResult();
    }
	
	public function beforeUpdate($data) {
		return $data;
	}
}
?>