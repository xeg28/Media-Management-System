<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\SharedImageModel;

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
        $image = $this->table($this->table)
                ->select("images.*, 0 as 'is_shared', 'image' as 'filetype'")
                ->getWhere(['id' => $id, 'user_id' => $user_id])
                ->getRow();

        $shared_model= new SharedImageModel();
        if(!$image) {
            return $shared_model->getSharedImage($id);
        }
        return $image;
    }

    public function saveImage($image) {
        $this->save($image);
    }

    // select images.*, users.email as 'sender_email', 1 as 'is_shared' from images
    // inner join shared_images
    // on shared_images.image_id = images.id
    // inner join users
    // on users.id = shared_images.sender_id
    // where shared_images.receiver_id = 1
    // UNION ALL
    // SELECT *, 0 as 'email', 0 as "is_shared" from images
    // where user_id = 1;

    public function getLastTenUpdated() {
		$user_id = session()->get("id");
        $builder1 = $this->db->table($this->table)
                    ->select("images.*, 1 as 'is_shared', users.email as 'sender_email'")
                    ->join('shared_images', 'images.id = shared_images.image_id')
                    ->join('users', 'users.id = shared_images.sender_id')
                    ->where('shared_images.receiver_id', $user_id);
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
		$sharedImgModel = new SharedImageModel();
		$sharedImgModel->deleteByImageId($id);
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