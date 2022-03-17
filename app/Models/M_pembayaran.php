<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class M_pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['total','metode_pembayaran','jumlah_dibayar','id_penjualan'];
}