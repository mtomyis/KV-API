<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\M_menu;


class C_stok extends ResourceController
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
        $id_kategori=0;
        switch ($input['id_kategori']) {
            case 'Snack':
                $id_kategori=1;
                break;
            case 'Main Course':
                $id_kategori=2;
                break;
            case 'Soup':
                $id_kategori=3;
                break;
            case 'Pizza':
                $id_kategori=4;
                break;
            case 'Dessert':
                $id_kategori=5;
                break;
            case 'Cold Drink':
                $id_kategori=6;
                break;
            case 'Soft Drink':
                $id_kategori=7;
                break;
            case 'Hot Drink':
                $id_kategori=8;
                break;
            case 'Beer and Mix Max':
                $id_kategori=9;
                break;
            case 'Campina':
                $id_kategori=10;
                break;
            case 'Miscellaneous':
                $id_kategori=11;
                break;
            default:
                break;
        }
        if ($input['gambar']=="") {
            $data = [
                'id_kategori' => $id_kategori,
                'nama'  => $input['nama'],
                // 'harga_beli'  => $input['harga_beli'],
                'harga_jual'  => $input['harga_jual'],
                // 'satuan_barang'  => $input['satuan_barang'],
                // 'stock'  => $input['stock'],
                // 'gambar'  => $input['gambar'],
                // 'id_distributor'  => $input['id_distributor']
            ];
        }else{
            // dd($input['stock']);    
            // $img = json_encode($input['gambar'], JSON_INVALID_UTF8_IGNORE);
            $fileGambar = base64_decode($input['gambar'],true);
            $unik = md5(uniqid(rand(), true));
            $namaGambar = $unik . '.' . 'jpg';
            $path = "image/";
            //image uploading folder path
            $success = file_put_contents($path . $namaGambar, $fileGambar);

            $data = [
                'id_kategori' => $id_kategori,
                'nama'  => $input['nama'],
                // 'harga_beli'  => $input['harga_beli'],
                'harga_jual'  => $input['harga_jual'],
                // 'satuan_barang'  => $input['satuan_barang'],
                // 'stock'  => $input['stock'],
                'gambar'  => $namaGambar,
                // 'id_distributor'  => $input['id_distributor']
            ];
        }
        
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
        $data = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No menu found');
        }
    }

    // update
    public function update($id = null){
        $model = new M_menu();
        $input = $this->request->getRawInput();
        $id_kategori=0;
        switch ($input['id_kategori']) {
            case 'Snack':
                $id_kategori=1;
                break;
            case 'Main Course':
                $id_kategori=2;
                break;
            case 'Soup':
                $id_kategori=3;
                break;
            case 'Pizza':
                $id_kategori=4;
                break;
            case 'Dessert':
                $id_kategori=5;
                break;
            case 'Cold Drink':
                $id_kategori=6;
                break;
            case 'Soft Drink':
                $id_kategori=7;
                break;
            case 'Hot Drink':
                $id_kategori=8;
                break;
            case 'Beer and Mix Max':
                $id_kategori=9;
                break;
            case 'Campina':
                $id_kategori=10;
                break;
            case 'Miscellaneous':
                $id_kategori=11;
                break;
            default:
                break;
        }
        if ($input['gambar']=="") {
            $data = [
                'id_kategori' => $id_kategori,
                'nama'  => $input['nama'],
                // 'harga_beli'  => $input['harga_beli'],
                'harga_jual'  => $input['harga_jual'],
                // 'satuan_barang'  => $input['satuan_barang'],
                // 'stock'  => $input['stock'],
                // 'gambar'  => $input['gambar'],
                // 'id_distributor'  => $input['id_distributor']
            ];
        }else{
            // dd($input['stock']);    
            // $img = json_encode($input['gambar'], JSON_INVALID_UTF8_IGNORE);
            $fileGambar = base64_decode($input['gambar'],true);
            $unik = md5(uniqid(rand(), true));
            $namaGambar = $unik . '.' . 'jpg';
            $path = "image/";
            //image uploading folder path
            $success = file_put_contents($path . $namaGambar, $fileGambar);

            $data = [
                'id_kategori' => $id_kategori,
                'nama'  => $input['nama'],
                // 'harga_beli'  => $input['harga_beli'],
                'harga_jual'  => $input['harga_jual'],
                // 'satuan_barang'  => $input['satuan_barang'],
                // 'stock'  => $input['stock'],
                'gambar'  => $namaGambar,
                // 'id_distributor'  => $input['id_distributor']
            ];
        }
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
        // $data = $model->where('id', $id)->delete($id);
        $data = [
            'status_delete' => '1'
        ];
        $dataa = $model->update($id, $data);
        if($dataa){
            // $model->delete($id);
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

    public function safe_delete($id = null)
    {
        $model = new M_menu();
        $data = [
            'status_delete' => '1'
        ];
        $model->update($id, $data);

        $response = [
          'status'   => 200,
          'error'    => null,
          'messages' => [
              'success' => 'Menu delete successfully'
          ]
        ];
        return $this->respond($response);
    }


}