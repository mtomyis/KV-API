<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class M_penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['id_pelanggan','id_meja','id_user','pelanggan','jenis_transaksi','status','diskon','jenis_diskon'];
}