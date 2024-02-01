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
        $audio = $this->table($this->table)
                ->select("audios.*, 0 as 'is_shared', 'audio' as 'filetype'")
                ->getWhere(['id' => $id, 'user_id' => $user_id])
                ->getRow();

        $shared_model= new SharedAudioModel();
        if(!$audio) {
            return $shared_model->getSharedAudio($id);
        }
        return $audio;
    }

    public function saveAudio($audio) {
        $this->save($audio);
    }

    public function getLastTenUpdated() {
		$user_id = session()->get("id");
        $builder1 = $this->db->table($this->table)
                    ->select($this->table.".*, 1 as 'is_shared', users.email as 'sender_email'")
                    ->join('shared_'.$this->table, $this->table.'.id = shared_'.$this->table.'.audio_id')
                    ->join('users', 'users.id = shared_'.$this->table.'.sender_id')
                    ->where('shared_'.$this->table.'.receiver_id', $user_id);
        $builder2 = $this->db->table($this->table)
                    ->select('*')->select("0 as 'is_shared'")
                    ->select("0 as 'sender_email'")
                    ->where("user_id", $user_id);
        $builder1->union($builder2);

        $builder =$this->db->table('(' . $builder1->getCompiledSelect(false) . ') AS union_result');
        $builder->limit(10)
                ->orderBy('uploaded_at','DESC');
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