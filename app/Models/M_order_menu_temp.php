<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class M_order_menu_temp extends Model
{
    protected $table = 'order_menu_temp';
    protected $primaryKey = 'id';
    protected $useTimestamps = false;
    protected $allowedFields = ['id_menu','jumlah'];
}