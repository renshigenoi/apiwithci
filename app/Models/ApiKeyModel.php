<?php 

namespace App\Models;
use CodeIgniter\Model;

class ApiKeyModel extends Model 
{
    protected $useTimestamps    = true;
    protected $table            = 'api_keys';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['user_email', 'key_label', 'api_key', 'ip_whitelist', 'is_active', 'last_used_at'];
    protected $useSoftDeletes   = true;
    protected $returnType       = 'array';
}