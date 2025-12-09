<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table      = 'categories';
    protected $primaryKey = 'category_id';

    protected $allowedFields = [
        'name',
        'description'
    ];

    protected $returnType = 'array';
    protected $useTimestamps = false;

    /* =====================================================
       LẤY DANH MỤC THEO ID
       ===================================================== */
    public function getCategoryById($id)
    {
        return $this->where('category_id', $id)->first();
    }

    /* =====================================================
       LẤY TẤT CẢ DANH MỤC
       ===================================================== */
    public function getAllCategories()
    {
        return $this->orderBy('category_id', 'DESC')->findAll();
    }

    /* =====================================================
       TÌM KIẾM DANH MỤC THEO TỪ KHÓA
       ===================================================== */
    public function searchCategory($keyword)
    {
        return $this->groupStart()
                        ->like('name', $keyword)
                        ->orLike('description', $keyword)
                    ->groupEnd()
                    ->orderBy('category_id', 'DESC')
                    ->findAll();
    }

    /* =====================================================
       UPDATE DANH MỤC THEO ID
       ===================================================== */
    public function updateCategory($id, $data)
    {
        return $this->update($id, $data);
    }

    /* =====================================================
       XÓA DANH MỤC THEO ID
       ===================================================== */
    public function deleteCategory($id)
    {
        return $this->delete($id);
    }
}
