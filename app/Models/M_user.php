<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class M_user extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['nama','username','password','level','keterangan'];
}