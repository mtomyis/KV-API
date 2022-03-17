<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\M_menu;


class C_manual extends ResourceController
{
    use ResponseTrait;

    public function menubyk($id_kategori = 1){

        $model = new M_menu();
        $data = $model->where('id_kategori', $id_kategori)->find();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No menu found');
        }
    }
}