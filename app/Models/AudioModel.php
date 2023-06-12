<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\SharedAudioModel;

class AudioModel extends Model {
    protected $table = 'audios';
    protected $allowedFields = ['id', 'name', 'type', 'path', 'caption', 
                                'updated_at', 'duration', 'note', 'user_id'];
    // protected $beforeInsert = ['beforeInsert'];
    // protected $beforeUpdate = ['beforeUpdate'];
    protected $db;
    public function __construct() {
        $this->db = db_connect();
    }

    public function getAudio($id) {
        parent::__construct();
		$user_id = session()->get("id");
        return $this->where('id', $id)
			->where('user_id', $user_id)
			->first();
    }

    public function saveAudio($audio) {
        $this->save($audio);
    }

    public function getLastTenUpdated() {
		$user_id = session()->get('id');
        $builder = $this->db->table($this->table)
				->where('user_id', $user_id) 
				->orderBy('uploaded_at','DESC')->limit(10);
        $query = $builder->get();
        return $query->getResult();
    }

    public function getAllByName() {
		$user_id = session()->get('id');
        $builder = $this->db->table($this->table)
					->where('user_id', $user_id)
					->orderBy('name', 'ASC');
        $query = $builder->get();
        return $query->getResult();
    }

    public function getAudioQuery($query) {
        $result = $this->db->query($query);
        return $result->getResult();
    }

    public function deleteAudio($id) {
		$sharedAudModel = new SharedAudioModel();
		$sharedAudModel->deleteByAudioId($id);
        $this->delete($id);
    }

    public function getAudioPath($id) {
        parent::__construct();  
        
        $audio = $this->where('id', $id)->first();
        return (isset($audio)) ? $audio['path'] : null;
    }

    public function searchAudios($query) {
        $query = $this->db->escapeString($query);
		$user_id = session()->get('id');
		$builder = $this->db->table($this->table)
				    ->select('*')
					->select("'Audio' as filetype")
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