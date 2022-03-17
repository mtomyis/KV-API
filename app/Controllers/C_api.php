<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\M_menu;
use App\Models\M_penjualan;
use App\Models\M_order_menu_temp;
// use App\Models\M_order_menu;


class C_api extends ResourceController
{
    // public function __construct()
    // {
    //     $db = \Config\Database::connect();
    // }

    use ResponseTrait;

    public function menubyk($id_kategori = 1){

        $model = new M_menu();
        $data = $model->where('id_kategori', $id_kategori)->find();
        // $db = \Config\Database::connect();
        // $data = $db->query('SELECT*FROM menu');
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No menu found');
        }
    }

    public function ordermenu_temp(){
        $db = db_connect();
        $query = $db->query('SELECT menu.nama, menu.harga_jual, order_menu_temp.jenis_transaksi order_menu_temp.jumlah,
        (menu.harga_jual * order_menu_temp.jumlah) as total
        from menu join order_menu_temp 
        on menu.id=order_menu_temp.id_menu');
        $data = $query->getResult();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No menu found');
        }
    }

    public function ordermenu(){
        $input = $this->request->getRawInput();
        $id = $input['id_penjualan'];
        $db = db_connect();
        $query = $db->query('SELECT menu.nama, menu.harga_jual, order_menu.id, order_menu.jumlah,
        (menu.harga_jual * order_menu.jumlah) as total
        from menu join order_menu 
        on menu.id=order_menu.id_menu where order_menu.id_penjualan= '.$id.'');
        $data = $query->getResult();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No menu found');
        }
    }

    // hapus semua order temp a
    public function empty_ordermenu_temp(){
        $model = new M_order_menu_temp();
        $data = $model->emptyTable();
        if($data){
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
    // hapus semua order temp b

    public function detail_simpan($id = null){
        $db = db_connect();

        $builder2 = $db->table('menu');
        $builder2->select('menu.nama, order_menu.jumlah, (menu.harga_jual * order_menu.jumlah) as total');
        $builder2->join('order_menu', 'order_menu.id_menu = menu.id');
        $builder2->where('order_menu.id_penjualan', $id);
        $hasil = $builder2->get()->getResult();

        return $this->respond($hasil);
    }

    public function order_menu_temp_old(){
        $input = $this->request->getRawInput();
        $id = $input['id_penjualan'];
        $db = db_connect();
        $response_nya= array();
        $query = $db->query('SELECT menu.nama, menu.harga_jual, order_menu.id, order_menu.jumlah,
        (menu.harga_jual * order_menu.jumlah) as total
        from menu join order_menu 
        on menu.id=order_menu.id_menu where order_menu.id_penjualan= '.$id.'');

        $query_tagihan = $db->query('SELECT SUM(menu.harga_jual * order_menu.jumlah) as total_tagihan
        from menu join order_menu 
        on menu.id=order_menu.id_menu where order_menu.id_penjualan= '.$id.'');

        $data = $query->getResult();
        $data_tagihan = $query_tagihan->getRow();

        $response_nya[] =array("total_tagihan"=>$data_tagihan->total_tagihan, "data_order"=>$data);

        if($data){
            return $this->respond($response_nya);
        }else{
            return $this->failNotFound('No menu found');
        }
    }

    public function history_penjualan()
    {
        // $model = new M_penjualan();
        // $model_order = new M_order_menu();
        $db = db_connect();
    	// $now = date("Y-m-d", strtotime('now'));
        $builder = $db->table('penjualan');
        $builder->select('created_at');
        $builder->where('status', '1');
		$builder->orderBy('id', 'desc');
		$builder->limit(1);
        $query_tgl = $builder->get()->getRow();

    	$now = date("Y-m-d", strtotime($query_tgl->created_at));
        
        $builder = $db->table('penjualan');
        $builder->select('meja.nama as nama_meja, penjualan.*');
        $builder->join('meja', 'meja.id = penjualan.id_meja');
        $builder->where('status', '1');
        $builder->like('penjualan.created_at', $now);
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

    public function void($id = null)
    {
        $model = new M_penjualan();
        $input = $this->request->getRawInput();
        $data = [
            'status' => $input['status']
        ];
        $model->update($id, $data);
        $response = [
          'status'   => 200,
          'error'    => null,
          'messages' => [
              'success' => 'Status updated successfully'
          ]
      ];
      return $this->respond($response);
    }

}