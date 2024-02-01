<?php namespace App\Models;

use CodeIgniter\Model;

class SharedAudioModel extends Model {
    protected $table = 'shared_Audios';
    protected $allowedFields = ['id', 'sender_id', 'receiver_id', 'audio_id'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $db;
    public function __construct() {
        $this->db = db_connect();
        
    }

    public function saveSharedAudio($audio) {
        $this->save($audio);
    }
	
	public function getSharedAudio($id) {
		$user_id = session()->get('id');
		$builder = $this->db->table($this->table)
				->select([
					$this->table.'.id',
					$this->table.'.shared_at',
					'users.email as sender_email',
					"1 as 'is_shared'",
					'audios.*', 
					"'audio' as 'filetype'"
				])
				->join('users', $this->table.'.sender_id = users.id', 'left')
				->join('audios', $this->table.'.audio_id = audios.id', 'left')
				->where($this->table.'.receiver_id', $user_id)
				->where('audios.id', $id);
		$row = $builder->get()->getRow();
		return $row;
	}
    public function getSharedAudios() {
		$user_id = session()->get("id");

		$builder = $this->db->table($this->table)
			->select([
				$this->table.'.id',
				$this->table.'.shared_at',
				'users.email as sender_email',
				'audios.*'
			])
			->join('users', $this->table.'.sender_id = users.id', 'left')
			->join('audios', $this->table.'.audio_id = audios.id', 'left')
			->where($this->table.'.receiver_id', $user_id)
			->orderBy('shared_at', 'DESC');

		$query = $builder->get();

		return $query->getResult();
    }

	public function getLastTenSharedAudios() {
		$user_id = session()->get("id");

		$builder = $this->db->table($this->table)
			->select([
				$this->table.'.id',
				$this->table.'.shared_at',
				'users.email as sender_email',
				'audios.*'
			])
			->join('users', $this->table.'.sender_id = users.id', 'left')
			->join('audios', $this->table.'.audio_id = audios.id', 'left')
			->where($this->table.'.receiver_id', $user_id)
			->orderBy('shared_at', 'DESC')
			->limit(10);

		$query = $builder->get();

		return $query->getResult();
    }
	
	public function sharedAudioExists($data) {
		$results = $this->db->table($this->table)->where($data)->countAllResults();
		
		return !($results == 0);
	}
	
	public function deleteByAudioId($id) {
		$this->db->table($this->table)
        ->where('audio_id', $id)
        ->delete();
	}

    public function deleteSharedAudio($id) {
        $this->delete($id);
    }

    public function searchSharedAudios($query) {
        $query = $this->db->escapeString($query);
		$user_id = session()->get("id");
        $words = null;
		$builder = $this->db->table($this->table)
				->select([
					$this->table.'.id',
					$this->table.'.shared_at',
					'users.email as sender_email',
					'audios.*',
					"'Audio' as filetype"
				])
				->join('users', $this->table.'.sender_id = users.id', 'left')
				->join('audios', $this->table.'.audio_id = audios.id', 'left')
				->where($this->table.'.receiver_id', $user_id);
        if(str_contains($query, " ")) {
            $words = explode(" ", $query);
        }

		$builder->groupStart()->like('audios.name', $query);

        if(!empty($words)) {
            foreach($words as $word) {
                if(trim($word) == '') {
                    continue;
                }
				$builder->orLike('audios.name', $word);
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