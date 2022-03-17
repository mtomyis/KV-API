<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\M_menu;


class C_menu extends ResourceController
{
    use ResponseTrait;

    // all users
    public function index(){
      $model = new M_menu();
      $data = $model->orderBy('id', 'DESC')->findAll();
      return $this->respond($data);
    }

    // create
    public function create() {
        $model = new M_menu();
        $input = $this->request->getRawInput();
        $data = [
            'id_kategori' => $input['id_kategori'],
            'nama'  => $input['nama'],
            'harga_beli'  => $input['harga_beli'],
            'harga_jual'  => $input['harga_jual'],
            'satuan_barang'  => $input['satuan_barang'],
            'stock'  => $input['stock'],
            'gambar'  => $input['gambar'],
            'id_distributor'  => $input['id_distributor']
        ];
        $model->insert($data);
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
              'success' => 'Menu created successfully'
          ]
      ];
      return $this->respondCreated($response);
    }

    // single user
    public function show($id = null){
        $model = new M_menu();
        $array = ['id_kategori' => $id, 'status_delete' => 0];
        $data = $model->where($array)->find();
        // $db = \Config\Database::connect();
        // $data = $db->query('SELECT*FROM menu');
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No menu found');
        }
        // $data = $model->where('id', $id)->first();
        // if($data){
        //     return $this->respond($data);
        // }else{
        //     return $this->failNotFound('No menu found');
        // }
    }

    // update
    public function update($id = null){
        $model = new M_menu();
        // $id = $this->request->getVar('id');
        $input = $this->request->getRawInput();

        $data = [
            'id_kategori' => $input['id_kategori'],
            'nama'  => $input['nama'],
            'harga_beli'  => $input['harga_beli'],
            'harga_jual'  => $input['harga_jual'],
            'satuan_barang'  => $input['satuan_barang'],
            'stock'  => $input['stock'],
            'gambar'  => $input['gambar'],
            'id_distributor'  => $input['id_distributor']
        ];
        $model->update($id, $data);
        $response = [
          'status'   => 200,
          'error'    => null,
          'messages' => [
              'success' => 'Menu updated successfully'
          ]
      ];
      return $this->respond($response);
    }

    // delete
    public function delete($id = null){
        $model = new M_menu();
        $data = $model->where('id', $id)->delete($id);
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Menu successfully deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No menu found');
        }
    }


}