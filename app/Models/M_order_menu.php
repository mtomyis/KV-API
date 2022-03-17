<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class M_order_menu extends Model
{
    protected $table = 'order_menu';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['id_menu','id_penjualan','jumlah','status'];
}