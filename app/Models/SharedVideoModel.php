<?php namespace App\Models;

use CodeIgniter\Model;

class SharedVideoModel extends Model {
    protected $table = 'shared_Videos';
    protected $allowedFields = ['id', 'sender_id', 'receiver_id', 'video_id'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $db;
    public function __construct() {
        $this->db = db_connect();
        
    }

    public function saveSharedVideo($video) {
        $this->save($video);
    }

	public function getSharedVideo($id) {
		$user_id = session()->get('id');
		$builder = $this->db->table($this->table)
				->select([
					$this->table.'.id',
					$this->table.'.shared_at',
					'users.email as sender_email',
					"1 as 'is_shared'",
					'videos.*', 
					"'video' as 'filetype'"
				])
				->join('users', $this->table.'.sender_id = users.id', 'left')
				->join('videos', $this->table.'.video_id = videos.id', 'left')
				->where($this->table.'.receiver_id', $user_id)
				->where('videos.id', $id);
		$row = $builder->get()->getRow();
		return $row;
	}

    public function getSharedVideos() {
		$user_id = session()->get("id");

		$builder = $this->db->table($this->table)
			->select([
				$this->table.'.id',
				$this->table.'.shared_at',
				'users.email as sender_email',
				'videos.*'
			])
			->join('users', $this->table.'.sender_id = users.id', 'left')
			->join('videos', $this->table.'.video_id = videos.id', 'left')
			->where($this->table.'.receiver_id', $user_id)
			->orderBy('shared_at', 'DESC');

		$query = $builder->get();

		return $query->getResult();
    }

	public function getLastTenSharedVideos() {
		$user_id = session()->get("id");

		$builder = $this->db->table($this->table)
			->select([
				$this->table.'.id',
				$this->table.'.shared_at',
				'users.email as sender_email',
				'videos.*'
			])
			->join('users', $this->table.'.sender_id = users.id', 'left')
			->join('videos', $this->table.'.video_id = videos.id', 'left')
			->where($this->table.'.receiver_id', $user_id)
			->orderBy('shared_at', 'DESC')
			->limit(10);

		$query = $builder->get();

		return $query->getResult();
    }
	
	public function sharedVideoExists($data) {
		$results = $this->db->table($this->table)->where($data)->countAllResults();
		
		return !($results == 0);
	}
	
	public function deleteByVideoId($id) {
		$this->db->table($this->table)
        ->where('video_id', $id)
        ->delete();
	}

    public function deleteSharedVideo($id) {
        $this->delete($id);
    }

    public function searchSharedVideos($query) {
        $query = $this->db->escapeString($query);
		$user_id = session()->get("id");
        $words = null;
		$builder = $this->db->table($this->table)
			->select([
				$this->table.'.id',
				$this->table.'.shared_at',
				'users.email as sender_email',
				'videos.*', 
				"'Video' as filetype"
			])
			->join('users', $this->table.'.sender_id = users.id', 'left')
			->join('videos', $this->table.'.video_id = videos.id', 'left')
			->where($this->table.'.receiver_id', $user_id);

        if(str_contains($query, " ")) {
            $words = explode(" ", $query);
        }

		$builder->groupStart()->like('videos.name', $query);

        if(!empty($words)) {
            foreach($words as $word) {
                if(trim($word) == '') {
                    continue;
                }
				$builder->orLike('videos.name', $word);
            }
        }
		$builder->groupEnd();

		$result = $builder->get();
        return $result->getResult();
        
    }
	
	public function beforeUpdate($data) {
		return $data;
	}
	
	public function beforeInsert($data) {
		return $data;
	}
 }
?>