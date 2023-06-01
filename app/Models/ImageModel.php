<?php namespace App\Models;

use CodeIgniter\Model;

class ImageModel extends Model {
    protected $table = 'images';
    protected $allowedFields = ['id', 'name', 'type', 'path', 'caption', 
								'updated_at', 'note', 'user_id'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $db;
    public function __construct() {
        $this->db = db_connect();
        
    }

    public function getImage($id) {
        parent::__construct();
		$user_id = session()->get("id");
        return $this->where('id', $id)
			->where('user_id', $user_id)
			->first();
    }

    public function saveImage($image) {
        $this->save($image);
    }

    public function getLastTenUpdated() {
		$user_id = session()->get("id");
        $builder = $this->db->table($this->table)->where('user_id', $user_id)->orderBy('uploaded_at','DESC')->limit(10);
        $query = $builder->get();
        return $query->getResult();
    }

    public function getAllByName() {
		$user_id = session()->get("id");
        $builder = $this->db->table($this->table)->where('user_id', $user_id)->orderBy('name', 'ASC');
        $query = $builder->get();
        return $query->getResult();
    }

    public function getImageQuery($query) {
        $db = db_connect();
        $result = $db->query($query);
        return $result->getResult();
    }

    public function deleteImage($id) {
        $this->delete($id);
    }

    public function getImagePath($id) {
        // Does not work without the parent construct call
        // If you add this to the contructor, it breaks the upload.
        parent::__construct();  
        
        $image = $this->where('id', $id)->first();
        return (isset($image)) ? $image['path'] : null;
    }

    public function searchImages($query) {
        $query = $this->db->escapeString($query);
		$user_id = session()->get("id");
        $words = null;
		$builder = $this->db->table($this->table)
					->select('*')
					->select("'N/A' as duration")
					->select("'Image' as filetype")
					->where('user_id', $user_id);
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