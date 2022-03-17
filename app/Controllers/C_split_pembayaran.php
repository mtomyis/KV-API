<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\M_split_pembayaran;


class C_split_pembayaran extends ResourceController
{
    use ResponseTrait;

    // all users
    public function index(){
      $model = new M_split_pembayaran();
      $data = $model->orderBy('id', 'DESC')->findAll();
      return $this->respond($data);
    }

    // create
    public function create() {
        $model = new M_split_pembayaran();
        $data = [
            'total'  => $this->request->getVar('total'),
            'metode_pembayaran'  => $this->request->getVar('metode_pembayaran'),
            'jumlah_dibayar'  => $this->request->getVar('jumlah_dibayar'),
            'id_penjualan'  => $this->request->getVar('id_penjualan')
        ];
        $model->insert($data);
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
              'success' => 'Split_pembayaran created successfully'
          ]
      ];
      return $this->respondCreated($response);
    }

    // single user
    public function show($id = null){
        $model = new M_split_pembayaran();
        $data = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No split_pembayaran found');
        }
    }

    // update
    public function update($id = null){
        $model = new M_split_pembayaran();
        // $id = $this->request->getVar('id');
        $input = $this->request->getRawInput();

        $data = [
            'total'  => $input['total'],
            'metode_pembayaran'  => $input['metode_pembayaran'],
            'jumlah_dibayar'  => $input['jumlah_dibayar'],
            'id_penjualan'  => $input['id_penjualan']
        ];
        $model->update($id, $data);
        $response = [
          'status'   => 200,
          'error'    => null,
          'messages' => [
              'success' => 'Split_pembayaran updated successfully'
          ]
      ];
      return $this->respond($response);
    }

    // delete
    public function delete($id = null){
        $model = new M_split_pembayaran();
        $data = $model->where('id', $id)->delete($id);
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Split_pembayaran successfully deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No split_pembayaran found');
        }
    }

}