<?php

namespace App\Models;

use CodeIgniter\Model;

class StoreUserModel extends Model
{
    protected $table            = 'store_users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'store_id', 'role', 'is_default'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Mendapatkan daftar toko yang bisa diakses oleh user tertentu
     */
    public function getStoresByUser($userId)
    {
        return $this->select('store_users.role as store_role, stores.*')
                    ->join('stores', 'stores.id = store_users.store_id')
                    ->where('store_users.user_id', $userId)
                    ->findAll();
    }

    // Fungsi tambahan untuk membantu reset default toko lain saat ada toko baru yang jadi default
    public function setDefaultStore($userId, $storeId)
    {
        // Set semua toko user ini jadi 0 dulu
        $this->where('user_id', $userId)->set(['is_default' => 0])->update();
        // Baru set yang dipilih jadi 1
        return $this->where(['user_id' => $userId, 'store_id' => $storeId])
                    ->set(['is_default' => 1])
                    ->update();
    }
}