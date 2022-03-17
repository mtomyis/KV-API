<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\M_order_menu_temp;


class C_order_menu_temp extends ResourceController
{
    use ResponseTrait;

    // all users
    public function index(){
        $db = db_connect();
        $response_nya= array();
        $query = $db->query('SELECT menu.nama, menu.harga_jual, order_menu_temp.id, order_menu_temp.jumlah,
        (menu.harga_jual * order_menu_temp.jumlah) as total
        from menu join order_menu_temp 
        on menu.id=order_menu_temp.id_menu');

        $query_tagihan = $db->query('SELECT SUM(menu.harga_jual * order_menu_temp.jumlah) as total_tagihan
        from menu join order_menu_temp 
        on menu.id=order_menu_temp.id_menu');

        $data = $query->getResult();
        $data_tagihan = $query_tagihan->getRow();

        $response_nya[] =array("total_tagihan"=>$data_tagihan->total_tagihan, "data_order"=>$data);

        if($data){
            return $this->respond($response_nya);
        }else{
            return $this->failNotFound('No menu found');
        }
    //   $model = new M_order_menu_temp();
    //   $data = $model->orderBy('id', 'ASC')->findAll();
    //   return $this->respond($data);
    }

    // create
    public function create() {
        $model = new M_order_menu_temp();
        $input = $this->request->getRawInput();
        // $data = [
        //     'nama' => $input['nama'],
        $data = [
            'id_menu' => $input['id_menu'],
            'jumlah'  => 1
        ];
        $model->insert($data);
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
              'success' => 'Order_menu_temp created successfully'
          ]
      ];
      return $this->respondCreated($response);
    }

    // single user
    public function show($id = null){
        $model = new M_order_menu_temp();
        $data = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No order_menu_temp found');
        }
    }

    // update
    public function update($id = null){
        $model = new M_order_menu_temp();
        // $id = $input['id'];
        $input = $this->request->getRawInput();
        $data = [
            // 'id_menu' => $input['id_menu'],
            'jumlah'  => $input['jumlah']
        ];
        $model->update($id, $data);
        $response = [
          'status'   => 200,
          'error'    => null,
          'messages' => [
              'success' => 'Order_menu_temp updated successfully'
          ]
      ];
      return $this->respond($response);
    }

    // delete
    public function delete($id = null){
        $model = new M_order_menu_temp();
        $data = $model->where('id', $id)->delete($id);
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Order_menu_temp successfully deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No order_menu_temp found');
        }
    }

}