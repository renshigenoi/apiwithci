<?php

namespace App\Models;
use CodeIgniter\Model;

class StoreModel extends Model
{
    protected $useTimestamps    = true;
    protected $table            = 'store';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'email', 'phone', 'address', 'contact_person', 'contact_phone', 'code', 'status'];
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
}
