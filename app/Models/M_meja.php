<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class M_meja extends Model
{
    protected $table = 'meja';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['nama'];
}