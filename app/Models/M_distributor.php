<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class M_distributor extends Model
{
    protected $table = 'distributor';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['nama','nomor','email','alamat'];
}