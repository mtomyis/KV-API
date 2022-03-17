<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class M_split_pembayaran extends Model
{
    protected $table = 'split_pembayaran';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['total','metode_pembayaran','jumlah_dibayar','id_penjualan'];
}