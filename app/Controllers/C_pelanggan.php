<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\M_pelanggan;


class C_pelanggan extends ResourceController
{
    use ResponseTrait;

    // all users
    public function index(){
      $model = new M_pelanggan();
      $data = $model->orderBy('id', 'DESC')->findAll();
      return $this->respond($data);
    }

    // create
    public function create() {
        $model = new M_pelanggan();
        $input = $this->request->getRawInput();

        $data = [
            'nama'  => $input['nama']
        ];
        $model->insert($data);
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
              'success' => 'Pelanggan created successfully'
          ]
      ];
      return $this->respondCreated($response);
    }

    // single user
    public function show($id = null){
        $model = new M_pelanggan();
        $data = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No pelanggan found');
        }
    }

    // update
    public function update($id = null){
        $model = new M_pelanggan();
        // $id = $input['id'];
        $input = $this->request->getRawInput();

        $data = [
            'nama'  => $input['nama']
        ];
        $model->update($id, $data);
        $response = [
          'status'   => 200,
          'error'    => null,
          'messages' => [
              'success' => 'Pelanggan updated successfully'
          ]
      ];
      return $this->respond($response);
    }

    // delete
    public function delete($id = null){
        $model = new M_pelanggan();
        $data = $model->where('id', $id)->delete($id);
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Pelanggan successfully deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No pelanggan found');
        }
    }

}