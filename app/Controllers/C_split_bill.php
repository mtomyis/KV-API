<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\M_split_bill;


class C_split_bill extends ResourceController
{
    use ResponseTrait;

    // all users
    public function index(){
      $model = new M_split_bill();
      $data = $model->orderBy('id', 'DESC')->findAll();
      return $this->respond($data);
    }

    // create
    public function create() {
        $model = new M_split_bill();
        $input = $this->request->getRawInput();

        $data = [
            'id_menu' => $input['id_menu'],
            'jumlah'  => $input['jumlah'],
            'total'  => $input['total'],
            'metode_pembayaran'  => $input['metode_pembayaran'],
            'jumlah_dibayar'  => $input['jumlah_dibayar'],
            'id_penjualan'  => $input['id_penjualan']
        ];
        $model->insert($data);
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
              'success' => 'Split_bill created successfully'
          ]
      ];
      return $this->respondCreated($response);
    }

    // single user
    public function show($id = null){
        $model = new M_split_bill();
        $data = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No split_bill found');
        }
    }

    // update
    public function update($id = null){
        $model = new M_split_bill();
        // $id = $input['id'];
        $input = $this->request->getRawInput();

        $data = [
            'id_menu' => $input['id_menu'],
            'jumlah'  => $input['jumlah'],
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
              'success' => 'Split_bill updated successfully'
          ]
      ];
      return $this->respond($response);
    }

    // delete
    public function delete($id = null){
        $model = new M_split_bill();
        $data = $model->where('id', $id)->delete($id);
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Split_bill successfully deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No split_bill found');
        }
    }

}