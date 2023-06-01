<?php namespace App\Models;

use CodeIgniter\Model;

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

    public function getVideo($id) {
        parent::__construct();
		$user_id = session()->get("id");
        return $this->where('id', $id)
			->where('user_id', $user_id)
			->first();
    }

    public function saveVideo($video) {
        $this->save($video);
    }

    public function getLastTenUpdated() {
		$user_id = session()->get("id");
        $builder = $this->db->table($this->table)
				->where('user_id', $user_id)
				->orderBy('uploaded_at','DESC')->limit(10);
        $query = $builder->get();
        return $query->getResult();
    }

    public function getAllByName() {
		$user_id = session()->get("id");
		$builder = $this->db->table($this->table)
			->where('user_id', $user_id)
			->orderBy('name', 'ASC');
        $query = $builder->get();
        return $query->getResult();
    }

    public function getVideoQuery($query) {
        $result = $this->db->query($query);
        return $result->getResult();
    }

    public function deleteVideo($id) {
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
		$builder = $this->db->table($this->table)
				    ->select('*')
					->select("'Video' as filetype")
					->where('user_id', $user_id);
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