<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\M_meja;


class C_meja extends ResourceController
{
    use ResponseTrait;

    // all users
    public function index(){
      $model = new M_meja();
      $data = $model->orderBy('id', 'ASC')->findAll();
      return $this->respond($data);
    }

    // create
    public function create() {
        $model = new M_meja();
        $input = $this->request->getRawInput();
        $data = [
            'nama' => $input['nama']
        ];
        $model->insert($data);
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
              'success' => 'Meja created successfully'
          ]
      ];
      return $this->respondCreated($response);
    }

    // single user
    public function show($id = null){
        $model = new M_meja();
        $data = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No meja found');
        }
    }

    // update
    public function update($id = null){
        $model = new M_meja();
        $input = $this->request->getRawInput();
        $data = [
            'nama' => $input['nama']
        ];
        $model->update($id, $data);
        $response = [
          'status'   => 200,
          'error'    => null,
          'messages' => [
              'success' => 'Meja updated successfully'
          ]
      ];
      return $this->respond($response);
    }

    // delete
    public function delete($id = null){
        $model = new M_meja();
        $data = $model->where('id', $id)->delete($id);
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Meja successfully deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No meja found');
        }
    }

}