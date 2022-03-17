<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\M_penjualan;
use App\Models\M_order_menu;
use App\Models\M_order_menu_temp;


class C_penjualan extends ResourceController
{
    use ResponseTrait;

    // all users
    public function index(){
        $model = new M_penjualan();
        $model_order = new M_order_menu();
        $db = db_connect();
        
        $builder = $db->table('penjualan');
        $builder->select('meja.nama as nama_meja, penjualan.*');
        $builder->join('meja', 'meja.id = penjualan.id_meja');
        $builder->where('status', '0');
        $builder->orderBy('penjualan.id', 'DESC');
        $query = $builder->get()->getResult();
        // dd($query);

        foreach ($query as $row)
        {
            $builder2 = $db->table('menu');
            $builder2->select('SUM(menu.harga_jual * order_menu.jumlah) as total_tagihan');
            $builder2->join('order_menu', 'order_menu.id_menu = menu.id');
            $builder2->where('order_menu.id_penjualan', $row->id);
            $query2 = $builder2->get()->getRow();

            $hasil[] = array(
                "id"=>$row->id, 
                "id_meja"=>$row->id_meja, 
                "nama_meja"=>$row->nama_meja,
                "id_admin"=>$row->id_user, 
                "pelanggan"=>$row->pelanggan, 
                "jenis_transaksi"=>$row->jenis_transaksi, 
                "status"=>$row->status,
                "diskon"=>$row->diskon, 
                "jenis_diskon"=>$row->jenis_diskon, 
                "created_at"=>$row->created_at, 
                "total_tagihan"=>$query2->total_tagihan
            );

        }
        return $this->respond($hasil);
    }


    // create
    public function create() {
        $model = new M_penjualan();
        $model_order = new M_order_menu();
        $model_order_temp = new M_order_menu_temp();
        
        $input = $this->request->getRawInput();

        switch ($input['jenis_diskon']) {
            case 'Persen':
                $jenis_diskon = 1;
                break;
            case 'Rupiah':
                $jenis_diskon = 2;    
                break;
            case '':
                $jenis_diskon = 0;
                break;
            default:
            $jenis_diskon = 0;
                break;
        }

        $data = [
            'id_meja'  => $input['id_meja'],
            'id_user'  => $input['id_user'],
            'pelanggan'  => $input['pelanggan'],
            'jenis_transaksi'  => 0,
            'status'  => 0,
            'diskon'  => $input['diskon'],
            'jenis_diskon'  => $jenis_diskon
        ];
        $model->insert($data);

        $qry_id_terakhir = $model->orderBy('id', 'DESC')->limit(1)->first();
        $id_jual_terakhir = $qry_id_terakhir['id'];

        $data_ordermenu_temp = $model_order_temp->orderBy('id', 'ASC')->findAll();

        foreach ($data_ordermenu_temp as $ordermenu_temp) {
            $data_order = array(
                "id_menu" => $ordermenu_temp['id_menu'],
                "id_penjualan" => $id_jual_terakhir,
                "jumlah" => $ordermenu_temp['jumlah'],
                "status" => 0
            );
            $model_order->insert($data_order);
        }

        // hapus data tbl temp
        $model_order_temp->emptyTable();

        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
              'success' => 'Penjualan created successfully'
          ]
      ];
      return $this->respondCreated($response);
    }

    // single user
    public function show($id = null){
        $model = new M_penjualan();
        $data = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No penjualan found');
        }
    }

    // update
    public function update($id = null){
        $model = new M_penjualan();
        $model_order = new M_order_menu();
        $model_order_temp = new M_order_menu_temp();
        
        $input = $this->request->getRawInput();

        switch ($input['jenis_diskon']) {
            case 'Persen':
                $jenis_diskon = 1;
                break;
            case 'Rupiah':
                $jenis_diskon = 2;    
                break;
            case '':
                $jenis_diskon = 0;
                break;
            default:
            $jenis_diskon = 0;
                break;
        }

        $data = [
            'id_meja'  => $input['id_meja'],
            'id_user'  => $input['id_user'],
            'pelanggan'  => $input['pelanggan'],
            'jenis_transaksi'  => 0,
            'status'  => 0,
            'diskon'  => $input['diskon'],
            'jenis_diskon'  => $jenis_diskon
        ];
        $model->update($id, $data);

        $data_ordermenu_temp = $model_order_temp->orderBy('id', 'ASC')->findAll();

        foreach ($data_ordermenu_temp as $ordermenu_temp) {
            $data_order = array(
                "id_menu" => $ordermenu_temp['id_menu'],
                "id_penjualan" => $id,
                "jumlah" => $ordermenu_temp['jumlah'],
                "status" => 0
            );
            $model_order->insert($data_order);
        }

        $model_order_temp->emptyTable();

        $response = [
          'status'   => 200,
          'error'    => null,
          'messages' => [
              'success' => 'Penjualan updated successfully'
            ]
        ];
      return $this->respond($response);
    }

    // delete
    public function delete($id = null){
        $model = new M_penjualan();
        $model_order = new M_order_menu();

        $data = $model->where('id', $id)->delete($id);
        $data_order = $model_order->where('id_penjualan', $id)->delete();

        if($data_order){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Penjualan successfully deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No penjualan found');
        }
    }

    

}