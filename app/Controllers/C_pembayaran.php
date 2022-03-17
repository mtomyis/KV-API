<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\M_pembayaran;
use App\Models\M_penjualan;


class C_pembayaran extends ResourceController
{
    use ResponseTrait;

    // all users
    public function index(){
      $model = new M_pembayaran();
      $data = $model->orderBy('id', 'DESC')->findAll();
      return $this->respond($data);
    }

    // create
    public function create() {
        $model = new M_pembayaran();
        $model_penjualan = new M_penjualan();
        $input = $this->request->getRawInput();
        $data = [
            'total'  => $input['total'],
            'metode_pembayaran'  => $input['metode_pembayaran'],
            'jumlah_dibayar'  => $input['jumlah_dibayar'],
            'id_penjualan'  => $input['id_penjualan']
        ];

        $model->insert($data);

        $model_penjualan->set('status', '1');
        $model_penjualan->where('id', $input['id_penjualan']);
        $model_penjualan->update();

        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
              'success' => 'Pembayaran created successfully'
          ]
      ];
      return $this->respondCreated($response);
    }

    // single user
    public function show($id = null){
        $model = new M_pembayaran();
        $data = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No pembayaran found');
        }
    }

    // update
    public function update($id = null){
        $model = new M_pembayaran();
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
              'success' => 'Pembayaran updated successfully'
          ]
      ];
      return $this->respond($response);
    }

    // delete
    public function delete($id = null){
        $model = new M_pembayaran();
        $data = $model->where('id', $id)->delete($id);
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Pembayaran successfully deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No pembayaran found');
        }
    }

}