<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\M_order_menu;


class C_order_menu extends ResourceController
{
    use ResponseTrait;

    // all users
    public function index(){
      $model = new M_order_menu();
      $data = $model->orderBy('id')->findAll();
      return $this->respond($data);
    }

    // create
    public function create() {
        $model = new M_order_menu();
        $input = $this->request->getRawInput();
        
        $data = [
            'id_menu' => $input['id_menu'],
            'id_penjualan'  => $input['id_penjualan'],
            'jumlah'  => $input['jumlah'],
            'status'  => $input['status']
        ];
        $model->insert($data);
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
              'success' => 'Order_menu created successfully'
          ]
      ];
      return $this->respondCreated($response);
    }

    // single user
    public function show($id = null){
        $model = new M_order_menu();
        $data = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No order_menu found');
        }
    }

    // update
    public function update($id = null){
        $model = new M_order_menu();
        // $id = $input['id'];
        $input = $this->request->getRawInput();

        $data = [
            'id_menu' => $input['id_menu'],
            'id_penjualan'  => $input['id_penjualan'],
            'jumlah'  => $input['jumlah'],
            'status'  => $input['status']
        ];
        $model->update($id, $data);
        $response = [
          'status'   => 200,
          'error'    => null,
          'messages' => [
              'success' => 'Order_menu updated successfully'
          ]
      ];
      return $this->respond($response);
    }

    // delete
    public function delete($id = null){
        $model = new M_order_menu();
        $data = $model->where('id', $id)->delete($id);
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Order_menu successfully deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No order_menu found');
        }
    }

}