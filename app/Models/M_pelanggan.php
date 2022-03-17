<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class M_pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['nama'];
}