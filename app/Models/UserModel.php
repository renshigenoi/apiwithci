<?php

namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $useTimestamps    = true;
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'email', 'password', 'role', 'reset_token', 'reset_expires'];
    protected $returnType       = 'array';
}
