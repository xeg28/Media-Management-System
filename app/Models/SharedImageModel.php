<?php namespace App\Models;

use CodeIgniter\Model;

class SharedImageModel extends Model {
    protected $table = 'shared_images';
    protected $allowedFields = ['id', 'sender_id', 'receiver_id', 'image_id'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $db;
    public function __construct() {
        $this->db = db_connect();
        
    }

    public function saveSharedImage($image) {
        $this->save($image);
    }

	public function getSharedImage($id) {
		$user_id = session()->get('id');
		$builder = $this->db->table($this->table)
				->select([
					$this->table.'.id',
					$this->table.'.shared_at',
					'users.email as sender_email',
					"1 as 'is_shared'",
					'images.*', 
					"'image' as 'filetype'"
				])
				->join('users', $this->table.'.sender_id = users.id', 'left')
				->join('images', $this->table.'.image_id = images.id', 'left')
				->where($this->table.'.receiver_id', $user_id)
				->where('images.id', $id);
		$row = $builder->get()->getRow();
		return $row;
	}

    public function getSharedImages() {
		$user_id = session()->get("id");

		$builder = $this->db->table($this->table)
			->select([
				$this->table.'.id',
				$this->table.'.shared_at',
				'users.email as sender_email',
				'images.*'
			])
			->join('users', $this->table.'.sender_id = users.id', 'left')
			->join('images', $this->table.'.image_id = images.id', 'left')
			->where($this->table.'.receiver_id', $user_id)
			->orderBy('shared_at', 'DESC');

		$query = $builder->get();

		return $query->getResult();
    }

	public function getLastTenSharedImages() {
		$user_id = session()->get("id");

		$builder = $this->db->table($this->table)
			->select([
				$this->table.'.id',
				$this->table.'.shared_at',
				'users.email as sender_email',
				'images.*'
			])
			->join('users', $this->table.'.sender_id = users.id', 'left')
			->join('images', $this->table.'.image_id = images.id', 'left')
			->where($this->table.'.receiver_id', $user_id)
			->orderBy('shared_at', 'DESC')
			->limit(10);

		$query = $builder->get();

		return $query->getResult();
    }
	
	public function sharedImageExists($data) {
		$results = $this->db->table($this->table)->where($data)->countAllResults();
		
		return !($results == 0);
	}
	
	public function deleteByImageId($id) {
		$this->db->table($this->table)
        ->where('image_id', $id)
        ->delete();
	}

    public function deleteSharedImage($id) {
        $this->delete($id);
    }

    public function searchSharedImages($query) {
        $query = $this->db->escapeString($query);
		$user_id = session()->get("id");
        $words = null;
		$builder = $this->db->table($this->table)
					->select([
						$this->table.'.id',
						$this->table.'.shared_at',
						'users.email as sender_email',
						'images.*', 
						"'N/A' as duration", 
						"'Image' as filetype"
					])
					->join('users', $this->table.'.sender_id = users.id', 'left')
					->join('images', $this->table.'.image_id = images.id', 'left')
					->where($this->table.'.receiver_id', $user_id);
        if(str_contains($query, " ")) {
            $words = explode(" ", $query);
        }

		$builder->groupStart()->like('images.name', $query);

        if(!empty($words)) {
            foreach($words as $word) {
                if(trim($word) == '') {
                    continue;
                }
				$builder->orLike('images.name', $word);
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