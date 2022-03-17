<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class M_split_bill extends Model
{
    protected $table = 'split_bill';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['id_menu','jumlah','total','metode_pembayaran','jumlah_dibayar','id_penjualan'];
}