<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class M_menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['id_kategori','nama','harga_beli','harga_jual','satuan_barang','stock','gambar','status_delete','id_distributor'];
}